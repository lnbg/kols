<?php
namespace App\Http\Controllers;

use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller;

/**
 *  * @SWG\Swagger(
 *     basePath="/v1",
 *     host="api.kols-ammedia.local",
 *     schemes={"http"},
 *     @SWG\Info(
 *         version="1.0",
 *         title="Kols API",
 *         @SWG\Contact(name="Ken"),
 *     )
 * )
 */
class BaseController extends Controller
{
    use Helpers;
    //
}