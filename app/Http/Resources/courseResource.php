<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class courseResource extends JsonResource
{

    public $status;
    public $message;
    public $resource;


    public function __construct($status, $message, $resource)
    {
        parent::__construct($resource);
        $this->status  = $status;
        $this->message = $message;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'success' => $this->status,
            'message' => $this->message,
            'data' => $this->resource->map(function ($course) {
                return [
                    'id'              => $course->id,
                    'category_id'     => $course->category_id,
                    'nama_course'     => $course->nama_course,
                    // ubah path menjadi URL penuh
                    'image_link'      => $course->image_link
                        ? url('storage/' . str_replace('\\', '/', $course->image_link))
                        : null,
                    'slugs'           => $course->slugs,
                    'description'     => $course->description,
                    'isLimitedCourse' => $course->isLimitedCourse,
                    'start_date'      => $course->start_date,
                    'end_date'        => $course->end_date,
                    'maxEnrollment'   => $course->maxEnrollment,
                    'teacher' => $course->teacher->name,
                    'href' => url('course/'. $course->slugs )
                ];
            }),
        ];
    }
}
