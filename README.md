# Backend Test assignment



## Case description

When a DB would run out of diskspace no more consents can be stored in the PG database. Still these consents would be logged on our api servers in some error log. How can we get these consents parsed out of the logs and be added back to our database? 

## Assignment

Write a log file parser that would extract the data needed and store that back in to the consent database. Remember that we can allow about 500 connections to the database. The logfiles are large files could contain millions of records. 

- How would you solve this issue? What is the best solution you can think of?
- Describe any risks involved, if any.
- Write the log file parser.

## Example log file output category based consent

```
{"level":30,"time":1661472030179,"pid":774314,"hostname":"ns3205231","reqId":"69cdff27-1237-4bb7-8992-fdc59b0005ce","req":{"method":"POST","url":"/prod/consent","hostname":"api.cookiefirst.com","remoteAddress":"88.68.165.117","remotePort":50402},"msg":"incoming request"}
[request 69cdff27-1237-4bb7-8992-fdc59b0005ce] CF:: Incoming request: {
  path: '/prod/consent',
  query: [Object: null prototype] {},
  body: {
    preferences: {
      necessary: true,
      performance: true,
      functional: true,
      advertising: true
    },
    apiKey: 'a0aa9d2e-7cd3-4e4a-a320-2d779ca5ee1b',
    action: 'store',
    visitor_id: '',
    config_version: 'bda89e26-9548-4494-b1a3-fba991384850',
    visitor_country: 'DE',
    visitor_region: 'BW',
    consent_policy: 1,
    granular_metadata: null,
    url: 'https://www.wunschgutschein.de/'
  },
  headers: {
    'x-forwarded-for': '88.68.165.117, 10.108.33.74',
    'x-real-ip': '10.108.33.74',
    host: 'api.cookiefirst.com',
    connection: 'upgrade',
    'content-length': '356',
    'content-type': 'application/json',
    origin: 'https://www.wunschgutschein.de',
    'accept-encoding': 'gzip, deflate, br',
    accept: 'application/json',
    'user-agent': 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6 Mobile/15E148 Safari/604.1',
    referer: 'https://www.wunschgutschein.de/',
    'accept-language': 'de-DE,de;q=0.9',
    'x-forwarded-port': '443',
    'x-forwarded-proto': 'https',
    'x-remote-port': '59479',
    'x-remote-ip': '88.68.165.117',
    'x-remote-proto': 'https',
    forwarded: 'for=88.68.165.117; proto=https; host=api.cookiefirst.com',
    'x-iplb-unique-id': '5844A575:E857_D5200514:01BB_63080D1E_641D5F:24748'
  }
}  +0ms
[request 69cdff27-1237-4bb7-8992-fdc59b0005ce] CF:: Input  validated  +7ms
[request 69cdff27-1237-4bb7-8992-fdc59b0005ce] CF:: Fetched site using API key  +2ms
[request 69cdff27-1237-4bb7-8992-fdc59b0005ce] CF:: Validated request origin  +0ms
[request 69cdff27-1237-4bb7-8992-fdc59b0005ce] CF:: Retrieved or generated visitor ID  +0ms
[request 69cdff27-1237-4bb7-8992-fdc59b0005ce] CF:: Parsed UserAgent to detect visitor's device  +3ms
[request 69cdff27-1237-4bb7-8992-fdc59b0005ce] CF:: Save consent changes in DB  +0ms
[request 69cdff27-1237-4bb7-8992-fdc59b0005ce] CF:: DatabaseError: insert into "consent_changes" ("action", "consent_policy", "created_at", "device_brand", "device_browser", "device_model", "device_os", "device_type", "granular_metadata", "is_bulk_duplicate", "preferences", "referer", "site_uuid", "updated_at", "uuid", "version_uuid", "visitor_country", "visitor_id", "visitor_ip", "visitor_region", "visitor_user_agent") values ($1, $2, CURRENT_TIMESTAMP, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, CURRENT_TIMESTAMP, $13, $14, $15, $16, $17, $18, $19) - cannot execute INSERT in a read-only transaction
    at DB.executeQuery (/home/ubuntu/cf-api-prod/src/helpers/database/DB.ts:65:23)
    at runMicrotasks (<anonymous>)
    at processTicksAndRejections (node:internal/process/task_queues:96:5)
    at async Promise.all (index 0)
    at async insertConsentChanges (/home/ubuntu/cf-api-prod/src/helpers/database/insertConsentChanges.ts:27:3)
    at async saveConsent (/home/ubuntu/cf-api-prod/src/api/consent/saveConsent.ts:174:5)
    at async Object.requestHandler (/home/ubuntu/cf-api-prod/src/helpers/server/asServerRequestHandler.ts:42:23) {
  query: {
    method: 'insert',
    options: {},
    timeout: false,
    cancelOnTimeout: false,
    __knexQueryUid: '_I6HDCvC3XxtYunUZKeT7',
    sql: 'insert into "consent_changes" ("action", "consent_policy", "created_at", "device_brand", "device_browser", "device_model", "device_os", "device_type", "granular_metadata", "is_bulk_duplicate", "preferences", "referer", "site_uuid", "updated_at", "uuid", "version_uuid", "visitor_country", "visitor_id", "visitor_ip", "visitor_region", "visitor_user_agent") values (?, ?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?)',
    returning: undefined
  },
  host: 'postgresql-553de9ac-oa3c058b5.database.cloud.ovh.net'
}  +2ms
[request 69cdff27-1237-4bb7-8992-fdc59b0005ce] CF:: DatabaseError: insert into "consent_changes" ("action", "consent_policy", "created_at", "device_brand", "device_browser", "device_model", "device_os", "device_type", "granular_metadata", "is_bulk_duplicate", "preferences", "referer", "site_uuid", "updated_at", "uuid", "version_uuid", "visitor_country", "visitor_id", "visitor_ip", "visitor_region", "visitor_user_agent") values ($1, $2, CURRENT_TIMESTAMP, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, CURRENT_TIMESTAMP, $13, $14, $15, $16, $17, $18, $19) - cannot execute INSERT in a read-only transaction
    at DB.executeQuery (/home/ubuntu/cf-api-prod/src/helpers/database/DB.ts:65:23)
    at runMicrotasks (<anonymous>)
    at processTicksAndRejections (node:internal/process/task_queues:96:5)
    at async Promise.all (index 0)
    at async insertConsentChanges (/home/ubuntu/cf-api-prod/src/helpers/database/insertConsentChanges.ts:27:3)
    at async saveConsent (/home/ubuntu/cf-api-prod/src/api/consent/saveConsent.ts:174:5)
    at async Object.requestHandler (/home/ubuntu/cf-api-prod/src/helpers/server/asServerRequestHandler.ts:42:23) {
  query: {
    method: 'insert',
    options: {},
    timeout: false,
    cancelOnTimeout: false,
    __knexQueryUid: '_I6HDCvC3XxtYunUZKeT7',
    sql: 'insert into "consent_changes" ("action", "consent_policy", "created_at", "device_brand", "device_browser", "device_model", "device_os", "device_type", "granular_metadata", "is_bulk_duplicate", "preferences", "referer", "site_uuid", "updated_at", "uuid", "version_uuid", "visitor_country", "visitor_id", "visitor_ip", "visitor_region", "visitor_user_agent") values (?, ?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?)',
    returning: undefined
  },
  host: 'postgresql-553de9ac-oa3c058b5.database.cloud.ovh.net'
}  +0ms
[request 69cdff27-1237-4bb7-8992-fdc59b0005ce] CF:: Response: {
  status: 500,
  headers: { 'Content-Type': 'application/json' },
  body: { data: 'Server error' }
}  +0ms
CF:: Execution time: 15ms
{"level":30,"time":1661472030195,"pid":774314,"hostname":"ns3205231","reqId":"69cdff27-1237-4bb7-8992-fdc59b0005ce","res":{"statusCode":500},"responseTime":16.30810546875,"msg":"request completed"}
{"level":30,"time":1661472030451,"pid":774314,"hostname":"ns3205231","reqId":"58fb9eac-3aba-4377-9607-6766efc1b297","req":{"method":"OPTIONS","url":"/prod/consent","hostname":"api.cookiefirst.com","remoteAddress":"93.225.246.84","remotePort":50414},"msg":"incoming request"}
{"level":30,"time":1661472030451,"pid":774314,"hostname":"ns3205231","reqId":"58fb9eac-3aba-4377-9607-6766efc1b297","res":{"statusCode":204},"responseTime":0.2849693298339844,"msg":"request completed"}
{"level":30,"time":1661472030688,"pid":774314,"hostname":"ns3205231","reqId":"ad3f332b-4db9-4f2e-a4a7-9c459deded16","req":{"method":"OPTIONS","url":"/prod/consent","hostname":"api.cookiefirst.com","remoteAddress":"191.221.169.136","remotePort":50428},"msg":"incoming request"}
{"level":30,"time":1661472030689,"pid":774314,"hostname":"ns3205231","reqId":"ad3f332b-4db9-4f2e-a4a7-9c459deded16","res":{"statusCode":204},"responseTime":0.25292205810546875,"msg":"request completed"}
```
### Example of service based consent

```
{"method":"POST","url":"/prod/consent","hostname":"api.cookiefirst.com","remoteAddress":"81.231.9.165","remotePort":38696},"msg":"incoming request"}
[request a144a7fe-ba0c-4608-8402-71b8f7b9ecb9] CF:: Incoming request: {
  path: '/prod/consent',
  query: [Object: null prototype] {},
  body: {
    preferences: {
      cookiefirst: true,
      google_analytics: true,
      google_tag_manager: true,
      linkedin_ads: true,
      hubspot: true,
      twitter: true,
      'active-campaign': true,
      'email-marketing': true
    },
    apiKey: 'f2b649a4-0919-45b6-b165-cf38ea2dcd71',
    action: 'update',
    visitor_id: '3681313e-f159-40e8-9948-b077c76c7b1f',
    config_version: '124d6364-3d47-4d67-b621-afb8f679ce17',
    visitor_country: 'SE',
    visitor_region: 'M',
    consent_policy: 2,
    granular_metadata: {
      cookiefirst: [Object],
      google_analytics: [Object],
      google_tag_manager: [Object],
      linkedin_ads: [Object],
      hubspot: [Object],
      twitter: [Object],
      'active-campaign': [Object],
      'email-marketing': [Object]
    },
    url: 'https://hemmersbach.com/career/browse-jobs'
  },
  headers: {
    'x-forwarded-for': '81.231.9.165, 10.108.33.47',
    'x-real-ip': '10.108.33.47',
    host: 'api.cookiefirst.com',
    connection: 'upgrade',
    'content-length': '1510',
    'sec-ch-ua': '"Chromium";v="104", " Not A;Brand";v="99", "Google Chrome";v="104"',
    accept: 'application/json',
    'content-type': 'application/json',
    'sec-ch-ua-mobile': '?0',
    'user-agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',
    'sec-ch-ua-platform': '"Windows"',
    origin: 'https://hemmersbach.com',
    'sec-fetch-site': 'cross-site',
    'sec-fetch-mode': 'cors',
    'sec-fetch-dest': 'empty',
    'accept-encoding': 'gzip, deflate, br',
    'accept-language': 'en-GB,en;q=0.9,sv-SE;q=0.8,sv;q=0.7,en-US;q=0.6',
    'x-forwarded-port': '443',
    'x-forwarded-proto': 'https',
    'x-remote-port': '58224',
    'x-remote-ip': '81.231.9.165',
    'x-remote-proto': 'https',
    forwarded: 'for=81.231.9.165; proto=https; host=api.cookiefirst.com',
    'x-iplb-unique-id': '51E709A5:E370_D5200514:01BB_63080EFE_6437C8:24749'
  }
}  +0ms
[request a144a7fe-ba0c-4608-8402-71b8f7b9ecb9] CF:: Input  validated  +8ms
[request a144a7fe-ba0c-4608-8402-71b8f7b9ecb9] CF:: Fetched site using API key  +3ms
[request a144a7fe-ba0c-4608-8402-71b8f7b9ecb9] CF:: Validated request origin  +0ms
[request a144a7fe-ba0c-4608-8402-71b8f7b9ecb9] CF:: Retrieved or generated visitor ID  +0ms
[request a144a7fe-ba0c-4608-8402-71b8f7b9ecb9] CF:: Parsed UserAgent to detect visitor's device  +5ms
[request a144a7fe-ba0c-4608-8402-71b8f7b9ecb9] CF:: Save consent changes in DB  +1ms
[request a144a7fe-ba0c-4608-8402-71b8f7b9ecb9] CF:: DatabaseError: insert into "consent_changes" ("action", "consent_policy", "created_at", "device_brand", "device_browser", "device_model", "device_os", "device_type", "granular_metadata", "is_bulk_duplicate", "preferences", "referer", "site_uuid", "updated_at", "uuid", "version_uuid", "visitor_country", "visitor_id", "visitor_ip", "visitor_region", "visitor_user_agent") values ($1, $2, CURRENT_TIMESTAMP, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, CURRENT_TIMESTAMP, $13, $14, $15, $16, $17, $18, $19) - cannot execute INSERT in a read-only transaction
    at DB.executeQuery (/home/ubuntu/cf-api-prod/src/helpers/database/DB.ts:65:23)
    at runMicrotasks (<anonymous>)
    at processTicksAndRejections (node:internal/process/task_queues:96:5)
    at async Promise.all (index 0)
    at async insertConsentChanges (/home/ubuntu/cf-api-prod/src/helpers/database/insertConsentChanges.ts:27:3)
    at async saveConsent (/home/ubuntu/cf-api-prod/src/api/consent/saveConsent.ts:174:5)
    at async Object.requestHandler (/home/ubuntu/cf-api-prod/src/helpers/server/asServerRequestHandler.ts:42:23) {
  query: {
    method: 'insert',
    options: {},
    timeout: false,
    cancelOnTimeout: false,
    __knexQueryUid: '_CdKpNDKi8J7eMIKLNCnw',
    sql: 'insert into "consent_changes" ("action", "consent_policy", "created_at", "device_brand", "device_browser", "device_model", "device_os", "device_type", "granular_metadata", "is_bulk_duplicate", "preferences", "referer", "site_uuid", "updated_at", "uuid", "version_uuid", "visitor_country", "visitor_id", "visitor_ip", "visitor_region", "visitor_user_agent") values (?, ?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?)',
    returning: undefined
  },
  host: 'postgresql-553de9ac-oa3c058b5.database.cloud.ovh.net'
}  +2ms
[request a144a7fe-ba0c-4608-8402-71b8f7b9ecb9] CF:: DatabaseError: insert into "consent_changes" ("action", "consent_policy", "created_at", "device_brand", "device_browser", "device_model", "device_os", "device_type", "granular_metadata", "is_bulk_duplicate", "preferences", "referer", "site_uuid", "updated_at", "uuid", "version_uuid", "visitor_country", "visitor_id", "visitor_ip", "visitor_region", "visitor_user_agent") values ($1, $2, CURRENT_TIMESTAMP, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, CURRENT_TIMESTAMP, $13, $14, $15, $16, $17, $18, $19) - cannot execute INSERT in a read-only transaction
    at DB.executeQuery (/home/ubuntu/cf-api-prod/src/helpers/database/DB.ts:65:23)
    at runMicrotasks (<anonymous>)
    at processTicksAndRejections (node:internal/process/task_queues:96:5)
    at async Promise.all (index 0)
    at async insertConsentChanges (/home/ubuntu/cf-api-prod/src/helpers/database/insertConsentChanges.ts:27:3)
    at async saveConsent (/home/ubuntu/cf-api-prod/src/api/consent/saveConsent.ts:174:5)
    at async Object.requestHandler (/home/ubuntu/cf-api-prod/src/helpers/server/asServerRequestHandler.ts:42:23) {
  query: {
    method: 'insert',
    options: {},
    timeout: false,
    cancelOnTimeout: false,
    __knexQueryUid: '_CdKpNDKi8J7eMIKLNCnw',
    sql: 'insert into "consent_changes" ("action", "consent_policy", "created_at", "device_brand", "device_browser", "device_model", "device_os", "device_type", "granular_metadata", "is_bulk_duplicate", "preferences", "referer", "site_uuid", "updated_at", "uuid", "version_uuid", "visitor_country", "visitor_id", "visitor_ip", "visitor_region", "visitor_user_agent") values (?, ?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?)',
    returning: undefined
  },
  host: 'postgresql-553de9ac-oa3c058b5.database.cloud.ovh.net'
}  +0ms
[request a144a7fe-ba0c-4608-8402-71b8f7b9ecb9] CF:: Response: {
  status: 500,
  headers: { 'Content-Type': 'application/json' },
  body: { data: 'Server error' }
}  +1ms
CF:: Execution time: 20ms
{"level":30,"time":1661472510537,"pid":774314,"hostname":"ns3205231","reqId":"a144a7fe-ba0c-4608-8402-71b8f7b9ecb9","res":{"statusCode":500},"responseTime":21.293537139892578,"msg":"request completed"}
{"level":30,"time":1661472510959,"pid":774314,"hostname":"ns3205231","reqId":"74094f89-165a-42e6-9247-ec49afa41911","req":{"method":"OPTIONS","url":"/prod/consent","hostname":"api.cookiefirst.com","remoteAddress":"174.64.142.68","remotePort":38710},"msg":"incoming request"}
{"level":30,"time":1661472510959,"pid":774314,"hostname":"ns3205231","reqId":"74094f89-165a-42e6-9247-ec49afa41911","res":{"statusCode":204},"responseTime":0.2975921630859375,"msg":"request completed"}
{"level":30,"time":1661472511274,"pid":774314,"hostname":"ns3205231","reqId":"57834d30-2fe9-424e-89cb-0449dde7201f","req":
```
