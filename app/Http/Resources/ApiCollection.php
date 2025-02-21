<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ApiCollection extends ResourceCollection
{
    private $data;
    private $message;
    private $status;
    private $resultsPaginate;

    /**
     * Create a new resource instance with custom message and status.
     *
     * @param  mixed  $resource
     * @param  string  $message
     * @param  int  $status
     * @return void
     */
    public function __construct($data, $resultsPaginate, $status = 200)
    {
        $this->data = $data;
        $this->message = getApiResponseMessage($status);
        $this->status = $status;
        $this->resultsPaginate = $resultsPaginate;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        $pagination = $this->resultsPaginate instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? [
                'currentPage' => $this->resultsPaginate->currentPage(),
                'from' => $this->resultsPaginate->firstItem(),
                'to' => $this->resultsPaginate->lastItem(),
                'perPage' => $this->resultsPaginate->perPage(),
                'total' => $this->resultsPaginate->total(),
                'lastPage' => $this->resultsPaginate->lastPage(),
            ]
            : null;
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => [
                'data' => $this->data,
                $this->mergeWhen($pagination, [
                    'paginate' => $pagination
                ])

            ],
        ];
    }
}
