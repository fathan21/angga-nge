<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Sort field.
     *
     * @var string
     */
    protected $sortField;

    /**
     * Sort order.
     *
     * @var string
     */
    protected $sortOrder;

    /**
     *page.
     *
     * @var int
     */
    protected $page;

    /**
     * Per page.
     *
     * @var int
     */
    protected $perPage;

    /**
     * Search.
     *
     * @var string
     */
    protected $search;

    /**
     * Request.
     *
     * @var Request
     */
    protected $request;

    /**
     * Filter date.
     *
     * @var mixed
     */
    protected $filterDate;

    /**
     * Parse request.
     *
     * @param Request $request Request
     *
     * @return void
     */


    /**
     * Parse request.
     *
     * @param Request $request Request
     *
     * @return void
     */
    protected function parseRequest(Request $request)
    {
        $this->request = $request;

        if ($sort = $request->get('sort')) {
            $sort = explode('|', $sort);
            $this->sortField = array_shift($sort);
            $this->sortOrder = implode('', $sort);
        }

        if ($request->get('per_page')) {
            $this->perPage = (int) $request->get('per_page');
        }
        $this->page = $request->get('page');
        $this->search = $request->get('q');

        if ($request->get('start_date')) {
            $this->filterDate = [$request->get('start_date'), $request->get('end_date')];
        }
    }


    /**
     * Get current user.
     *
     * @return \App\Models\User
     */
    protected function user()
    {
        return auth_user();
    }


    public function error($code = 400, $message = null, $erors = [])  {
        
        $response = [
            'status' => 'error',
            'code' => $code,
            'erors' => $erors,
            'message' => @$message ?: 'error',
            'data' => null
        ];
        if (!empty($message)) {
            $response['message'] = $message;
        }
        return response()->json($response, $code);
    }

    public function success($result,$message='success', $code=200)  {
        
        $response = [
            'status' => 'success',
            'code' => $code,
            'message' => @$message ?: 'success',
            'data' => $result,
        ];
        if (!empty($message)) {
            $response['message'] = $message;
        }
        return response()->json($response, $code);
    }
}
