<?php

namespace App\Presenters;

use App\Transformers\YoutubeChannelTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class YoutubeChannelPresenter.
 *
 * @package namespace App\Presenters;
 */
class YoutubeChannelPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new YoutubeChannelTransformer();
    }
}
