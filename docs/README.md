# CDN PROXY Document

## Table of Contents

* [Bootstrap](#bootstrap)
    * [main](#main)
* [Client](#client)
    * [__construct](#__construct)
    * [execute](#execute)
    * [setSSLVerifyPeer](#setsslverifypeer)
    * [setUrl](#seturl)
    * [setHeaders](#setheaders)
    * [setTimeOut](#settimeout)
    * [setPostData](#setpostdata)
    * [setPort](#setport)
* [Filesystem](#filesystem)
    * [file_get_contents](#file_get_contents)
* [Host](#host)
    * [getSSLVerifyPeer](#getsslverifypeer)
    * [setSSLVerifyPeer](#setsslverifypeer-1)
    * [getRequestTimeOut](#getrequesttimeout)
    * [setRequestTimeOut](#setrequesttimeout)
    * [getOriginProtocolPolicy](#getoriginprotocolpolicy)
    * [setOriginProtocolPolicy](#setoriginprotocolpolicy)
    * [getHttpPort](#gethttpport)
    * [setHttpPort](#sethttpport)
    * [getHttpsPort](#gethttpsport)
    * [setHttpsPort](#sethttpsport)
    * [getCustomOriginHeaders](#getcustomoriginheaders)
    * [setCustomOriginHeader](#setcustomoriginheader)
    * [getForwardHeader](#getforwardheader)
    * [setForwardHeader](#setforwardheader)
    * [getForwardHeadersWhiteList](#getforwardheaderswhitelist)
    * [setForwardHeadersWhiteList](#setforwardheaderswhitelist)
* [Main](#main-1)
    * [__construct](#__construct-1)
    * [execute](#execute-1)
    * [getHostName](#gethostname)
    * [setHostName](#sethostname)
    * [getRootPath](#getrootpath)
    * [setRootPath](#setrootpath)
    * [setAllowHostName](#setallowhostname)
    * [hasAllowHostName](#hasallowhostname)
* [Network](#network)
    * [header](#header)
    * [stream_context_create](#stream_context_create)
* [Request](#request)
    * [getHostName](#gethostname-1)
    * [setHostName](#sethostname-1)
    * [getRootPath](#getrootpath-1)
    * [setRootPath](#setrootpath-1)
    * [setAllowHostName](#setallowhostname-1)
    * [hasAllowHostName](#hasallowhostname-1)
    * [getOriginContents](#getorigincontents)
    * [getHostConfig](#gethostconfig)
* [Response](#response)
    * [sendBody](#sendbody)
    * [sendHeader](#sendheader)

## Bootstrap





* Full name: \Suzunone\CDN\Bootstrap

**See Also:**

* https://github.com/suzunone/CDN * https://github.com/suzunone/CDN 

### main



```php
Bootstrap::main(  ): \Suzunone\CDN\Main
```



* This method is **static**.



---

## Client

HTTPRequestのClient



* Full name: \Suzunone\CDN\Http\Client

**See Also:**

* https://github.com/suzunone/CDN * https://github.com/suzunone/CDN 

### __construct

Client constructor.

```php
Client::__construct(  )
```







---

### execute



```php
Client::execute(  ): string
```







---

### setSSLVerifyPeer



```php
Client::setSSLVerifyPeer( boolean $setter )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$setter` | **boolean** |  |




---

### setUrl



```php
Client::setUrl( string $setter ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$setter` | **string** |  |




---

### setHeaders



```php
Client::setHeaders( array $headers ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$headers` | **array** |  |




---

### setTimeOut



```php
Client::setTimeOut( integer $setter ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$setter` | **integer** |  |




---

### setPostData



```php
Client::setPostData( mixed $setter ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$setter` | **mixed** |  |




---

### setPort

ポートを指定する

```php
Client::setPort( integer $setter ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$setter` | **integer** |  |




---

## Filesystem





* Full name: \Suzunone\CDN\Wrapper\Filesystem

**See Also:**

* https://github.com/suzunone/CDN * https://github.com/suzunone/CDN 

### file_get_contents



```php
Filesystem::file_get_contents( array $param ): mixed
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$param` | **array** |  |




---

## Host





* Full name: \Suzunone\CDN\Config\Host

**See Also:**

* https://github.com/suzunone/CDN * https://github.com/suzunone/CDN 

### getSSLVerifyPeer

SSL証明書の検証をするかどうか

```php
Host::getSSLVerifyPeer(  ): boolean
```







---

### setSSLVerifyPeer

SSL証明書の検証をするかどうか

```php
Host::setSSLVerifyPeer( boolean $ssl_verify_peer ): \Suzunone\CDN\Config\Host
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$ssl_verify_peer` | **boolean** |  |




---

### getRequestTimeOut

タイムアウト時間取得

```php
Host::getRequestTimeOut(  ): integer
```







---

### setRequestTimeOut

タイムアウト時間設定

```php
Host::setRequestTimeOut( integer $request_time_out ): \Suzunone\CDN\Config\Host
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$request_time_out` | **integer** |  |




---

### getOriginProtocolPolicy

オリジンへの取得ポリシーを取得する

```php
Host::getOriginProtocolPolicy(  ): integer
```






**See Also:**

* \Suzunone\CDN\Config\Host::HTTP_ONLY * \Suzunone\CDN\Config\Host::HTTPS_ONLY * \Suzunone\CDN\Config\Host::MATCH_VIEWER 

---

### setOriginProtocolPolicy

オリジンへの取得ポリシーをセットする

```php
Host::setOriginProtocolPolicy( integer $origin_protocol_policy ): self
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$origin_protocol_policy` | **integer** | (Domain::HTTP_ONLY OR Domain::HTTPS_ONLY OR Domain::MATCH_VIEWER) |



**See Also:**

* \Suzunone\CDN\Config\Host::HTTP_ONLY * \Suzunone\CDN\Config\Host::HTTPS_ONLY * \Suzunone\CDN\Config\Host::MATCH_VIEWER 

---

### getHttpPort

オリジンへのリクエストでHTTPのリクエストポートを取得する

```php
Host::getHttpPort(  ): string
```







---

### setHttpPort

オリジンへのリクエストでHTTPのリクエストポートを設定する

```php
Host::setHttpPort( string $http_port ): self
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$http_port` | **string** |  |




---

### getHttpsPort

オリジンへのリクエストでHTTPSのリクエストポートを取得する

```php
Host::getHttpsPort(  ): string
```







---

### setHttpsPort

オリジンへのリクエストでHTTPSのリクエストポートを設定する

```php
Host::setHttpsPort( string $https_port ): self
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$https_port` | **string** |  |




---

### getCustomOriginHeaders

カスタムヘッダを返す

```php
Host::getCustomOriginHeaders(  ): array
```







---

### setCustomOriginHeader

カスタムヘッダをセットする

```php
Host::setCustomOriginHeader( string $custom_origin_header, string|null $values = null ): self
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$custom_origin_header` | **string** |  |
| `$values` | **string&#124;null** |  |




---

### getForwardHeader

ブラウザからのリクエストヘッダの透過設定を返す

```php
Host::getForwardHeader(  ): integer
```






**See Also:**

* \Suzunone\CDN\Config\Host::FORWARD_ALL * \Suzunone\CDN\Config\Host::FORWARD_WHITE_LIST 

---

### setForwardHeader

ブラウザからのリクエストヘッダの透過設定を指定する

```php
Host::setForwardHeader( integer $forward_header ): self
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$forward_header` | **integer** |  |



**See Also:**

* \Suzunone\CDN\Config\Host::FORWARD_ALL * \Suzunone\CDN\Config\Host::FORWARD_WHITE_LIST 

---

### getForwardHeadersWhiteList

ブラウザからのリクエストヘッダの透過するヘッダを返す

```php
Host::getForwardHeadersWhiteList(  ): array
```







---

### setForwardHeadersWhiteList

ブラウザからのリクエストヘッダの透過設定をセットする

```php
Host::setForwardHeadersWhiteList( string $forward_headers_white_list ): self
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$forward_headers_white_list` | **string** |  |




---

## Main





* Full name: \Suzunone\CDN\Main

**See Also:**

* https://github.com/suzunone/CDN * https://github.com/suzunone/CDN 

### __construct

Main constructor.

```php
Main::__construct(  )
```







---

### execute

ブラウザから実行される処理

```php
Main::execute(  )
```







---

### getHostName

ホスト名の取得

```php
Main::getHostName(  ): string|null
```







---

### setHostName

ホスト名のセット

```php
Main::setHostName( string $host_name ): self
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$host_name` | **string** |  |




---

### getRootPath

きれいなURLが使えない場合、除外するルートパス(getter)

```php
Main::getRootPath(  ): string
```







---

### setRootPath

きれいなURLが使えない場合、除外するルートパス(setter)

```php
Main::setRootPath( string $root_path ): self
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$root_path` | **string** |  |




---

### setAllowHostName

許可するホスト名に追加する

```php
Main::setAllowHostName(  $host_name, \Suzunone\CDN\Config\Host|null $setting = null ): self
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$host_name` | **** | string 追加するホスト名 |
| `$setting` | **\Suzunone\CDN\Config\Host&#124;null** | デフォルトの設定でいい場合は省略 |



**See Also:**

* \Suzunone\CDN\Config\Host 

---

### hasAllowHostName

ホスト名が許可されているかどうか

```php
Main::hasAllowHostName( string $host_name ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$host_name` | **string** | 確認するホスト名 |




---

## Network





* Full name: \Suzunone\CDN\Wrapper\Network

**See Also:**

* https://github.com/suzunone/CDN * https://github.com/suzunone/CDN 

### header



```php
Network::header( array $param )
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$param` | **array** |  |




---

### stream_context_create



```php
Network::stream_context_create( array $param ): mixed
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$param` | **array** |  |




---

## Request





* Full name: \Suzunone\CDN\Http\Request

**See Also:**

* https://github.com/suzunone/CDN * https://github.com/suzunone/CDN 

### getHostName

ホスト名のセット

```php
Request::getHostName(  ): string|null
```







---

### setHostName

ホスト名のセット

```php
Request::setHostName( string $host_name ): self
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$host_name` | **string** |  |




---

### getRootPath

きれいなURLが使えない場合、除外するルートパス(getter)

```php
Request::getRootPath(  ): string
```







---

### setRootPath

きれいなURLが使えない場合、除外するルートパス(setter)

```php
Request::setRootPath( string $root_path ): self
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$root_path` | **string** |  |




---

### setAllowHostName

許可するホスト名に追加する

```php
Request::setAllowHostName(  $host_name, \Suzunone\CDN\Config\Host|null $setting = null ): self
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$host_name` | **** | string 追加するホスト名 |
| `$setting` | **\Suzunone\CDN\Config\Host&#124;null** | デフォルトの設定でいい場合は省略 |



**See Also:**

* \Suzunone\CDN\Config\Host 

---

### hasAllowHostName

ホスト名が許可されているかどうか

```php
Request::hasAllowHostName( string $host_name ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$host_name` | **string** | 確認するホスト名 |




---

### getOriginContents



```php
Request::getOriginContents(  ): \Suzunone\CDN\Http\Client
```







---

### getHostConfig

ホストコンフィグの取得

```php
Request::getHostConfig( string $host_name ): \Suzunone\CDN\Config\Host
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$host_name` | **string** |  |




---

## Response





* Full name: \Suzunone\CDN\Http\Response

**See Also:**

* https://github.com/suzunone/CDN * https://github.com/suzunone/CDN 

### sendBody

Bodyを送信する

```php
Response::sendBody( \Suzunone\CDN\Http\Client $Client )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$Client` | **\Suzunone\CDN\Http\Client** |  |




---

### sendHeader

ヘッダを送信する

```php
Response::sendHeader( \Suzunone\CDN\Http\Client $Client )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$Client` | **\Suzunone\CDN\Http\Client** |  |




---



--------
> This document was automatically generated from source code comments on 2018-04-17 using [phpDocumentor](http://www.phpdoc.org/) and [cvuorinen/phpdoc-markdown-public](https://github.com/cvuorinen/phpdoc-markdown-public)
