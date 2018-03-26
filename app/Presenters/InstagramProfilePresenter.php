<?php

namespace App\Presenters;

use App\Transformers\InstagramProfileTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class InstagramProfilePresenter.
 *
 * @package namespace App\Presenters;
 */
class InstagramProfilePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new InstagramProfileTransformer();
    }
}
