<p align="center">
    <h1 align="center">Price update API based on Yii 2 Advanced Project Template</h1>
    <br>
</p>

Methods
-------------------
<b>Function</b> /1.0/price/update?key=FOJShnewogf743fhdscvn3w4cs
<br/>

<br/>
<table class="table table-bordered table-striped">
<thead>
<tr>
<th>Parameter</th>
<th>Discription</th>
<th>Type</th>
<th>Required</th>
<th>Example</th>
</tr>
</thead>
<tbody>
<tr>
<td>data</td>
<td>Массив id продуктов и цен для них в каждом регионе</td>
<td>JSON</td>
<td>
Yes
</td>
<td><pre>
{
 "data" : [
   {
     "product_id": 1001001,
     "prices": {
       "1": {
         "price_purchase": 183330012,
         "price_selling": 120012,
         "price_discount": 11001
       }
     }
   }
 ]
}
</td>
</tr>
</tbody>
</table>

Usage
-------------------
<b>request</b>
<pre>
http://api.kf-test.dev/1.0/price/update?key=FOJShnewogf743fhdscvn3w4cs
</pre>

<b>response</b>
<pre>
{
    "status": 1,
    "message": "Обновлено 1 цена"
}
</pre>

DIRECTORY STRUCTURE
-------------------

```
common
    components/          contains access classes
    config/              contains shared configurations
    models/              contains model classes used in both backend and frontend
api
    config/              contains api configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    web/                 contains the entry script and Web resources
vendor/                  contains dependent 3rd-party packages
```