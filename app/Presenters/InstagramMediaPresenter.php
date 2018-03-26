<?php

namespace App\Presenters;

use App\Transformers\InstagramMediaTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class InstagramMediaPresenter.
 *
 * @package namespace App\Presenters;
 */
class InstagramMediaPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new InstagramMediaTransformer();
    }
}
