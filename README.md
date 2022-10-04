# Backend Test assignment

### For running this on local machine you need to run 

```
composer install

docker-compose up -d

php artisan migrate

php artisan serve 

php artisan queue:work

```
After that you will have two options how to run Parser. 
1. Via command ```php artisan log:parser --path=``` and give the path to log file
2. Go to the browser and open  http://127.0.0.1:8000/

### Example log file output

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

