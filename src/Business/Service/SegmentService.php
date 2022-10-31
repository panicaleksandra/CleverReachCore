<?php

namespace CleverReachCore\Business\Service;

use CleverReachCore\Core\BusinessLogic\Segment\Contracts\SegmentService as BaseSegmentService;
use CleverReachCore\Core\BusinessLogic\Segment\DTO\Segment;

/**
 * Class SegmentService
 *
 * @package CleverReachCore\Business\Service
 */
class SegmentService implements BaseSegmentService
{
    /**
     * Retrieves list of available segments.
     *
     * @return Segment[] The list of available segments.
     */
    public function getSegments(): array
    {
        return [];
    }

    /**
     * Returns segment filtered by condition
     *
     * @param string $filter
     *
     * @return Segment|null
     */
    public function getSegment($filter): ?Segment
    {
        return new Segment('', '', []);
    }
}
