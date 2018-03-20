<?php

namespace App\Presenters;

use App\Transformers\FacebookPostTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class FacebookPostPresenter.
 *
 * @package namespace App\Presenters;
 */
class FacebookPostPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new FacebookPostTransformer();
    }
}
