<?php

namespace App\Presenters;

use App\Transformers\FacebookProfileTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class FacebookProfilePresenter.
 *
 * @package namespace App\Presenters;
 */
class FacebookProfilePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new FacebookProfileTransformer();
    }
}
