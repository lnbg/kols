<?php

namespace App\Presenters;

use App\Transformers\YoutubeVideoTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class YoutubeVideoPresenter.
 *
 * @package namespace App\Presenters;
 */
class YoutubeVideoPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new YoutubeVideoTransformer();
    }
}
