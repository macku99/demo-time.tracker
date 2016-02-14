<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use League\Fractal\Manager as FractalManager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiController extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var FractalManager
     */
    protected $fractal;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var int
     */
    protected $statusCode = Response::HTTP_OK;

    /**
     * @var string
     */
    const CODE_INTERNAL_ERROR = '500';

    /**
     * @var string
     */
    const CODE_NOT_FOUND = '404';

    /**
     * @var string
     */
    const CODE_UNAUTHORIZED = '401';

    /**
     * @var string
     */
    const CODE_FORBIDDEN = '403';

    /**
     * @var string
     */
    const CODE_WRONG_ARGS = '400';

    /**
     * @param FractalManager $fractal
     * @param Request        $request
     */
    public function __construct(FractalManager $fractal, Request $request)
    {
        $this->fractal = $fractal;
        if ($request->has('includes')) {
            $this->fractal->parseIncludes($request->get('includes'));
        }

        $this->request = $request;
    }

    /**
     * @return int
     */
    public function statusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param  mixed $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param  mixed               $collection
     * @param  TransformerAbstract $transformer
     * @param  array               $headers
     * @return IlluminateResponse
     */
    protected function respondWithCollection($collection, TransformerAbstract $transformer, array $headers = [])
    {
        $resource = new Collection($collection, $transformer);

        if (is_subclass_of($collection, AbstractPaginator::class)) {
            if ($this->request->has('includes')) {
                $queryParams = array_diff_key($this->request->all(), array_flip(['page']));
                $collection->appends($queryParams);
            }

            $resource->setPaginator(new IlluminatePaginatorAdapter($collection));
        }
        $data = $this->fractal->createData($resource);

        return $this->respond($data->toArray(), $headers);
    }

    /**
     * @param  mixed               $item
     * @param  TransformerAbstract $transformer
     * @param  array               $headers
     * @return IlluminateResponse
     */
    protected function respondWithItem($item, TransformerAbstract $transformer, array $headers = [])
    {
        $resource = new Item($item, $transformer);
        $data = $this->fractal->createData($resource);

        return $this->respond($data->toArray(), $headers);
    }

    /**
     * Generates a Response with a 201 HTTP header and associate a location if provided.
     *
     * @param  string $location
     * @param  array  $headers
     * @return IlluminateResponse
     */
    protected function respondCreated($location = null, array $headers = [])
    {
        if ( ! is_null($location)) {
            $headers = array_merge($headers, [
                'Location' => $location,
            ]);
        }

        return $this->setStatusCode(Response::HTTP_CREATED)
                    ->respond(null, $headers);
    }

    /**
     * Generates a Response with a 202 HTTP header and associate a location and/or data if provided.
     *
     * @param  array|string $data
     * @param  string       $location
     * @param  array        $headers
     * @return IlluminateResponse
     */
    protected function respondAccepted($data = null, $location = null, array $headers = [])
    {
        if ( ! is_null($location)) {
            $headers = array_merge($headers, [
                'Location' => $location,
            ]);
        }

        return $this->setStatusCode(Response::HTTP_ACCEPTED)
                    ->respond($data, $headers);
    }

    /**
     * Respond to an API request with an error.
     *
     * @param  string $message
     * @param  string $errorCode
     * @param  array  $headers
     * @return IlluminateResponse
     */
    protected function respondWithError($message, $errorCode = null, array $headers = [])
    {
        return $this->respond([
            'error' => [
                'code'      => $errorCode,
                'http_code' => $this->statusCode(),
                'message'   => $message,
            ],
        ], $headers);
    }

    /**
     * Respond to an API request.
     *
     * @param  mixed $data
     * @param  array $headers
     * @return IlluminateResponse
     */
    protected function respond($data, array $headers = [])
    {
        return response($data, $this->statusCode(), $headers);
    }

    /**
     * Generates a Response with a 500 HTTP header and a given message.
     *
     * @param  string $message
     * @param  array  $headers
     * @return IlluminateResponse
     */
    protected function errorInternalError($message = 'Internal Error', array $headers = [])
    {
        return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                    ->respondWithError($message, self::CODE_INTERNAL_ERROR, $headers);
    }

    /**
     * Generates a Response with a 404 HTTP header and a given message.
     *
     * @param  string $message
     * @param  array  $headers
     * @return IlluminateResponse
     */
    protected function errorNotFound($message = 'Resource Not Found', array $headers = [])
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)
                    ->respondWithError($message, self::CODE_NOT_FOUND, $headers);
    }

    /**
     * Generates a Response with a 403 HTTP header and a given message.
     *
     * @param  string $message
     * @param  array  $headers
     * @return IlluminateResponse
     */
    protected function errorForbidden($message = 'Forbidden', array $headers = [])
    {
        return $this->setStatusCode(Response::HTTP_FORBIDDEN)
                    ->respondWithError($message, self::CODE_FORBIDDEN, $headers);
    }

    /**
     * Generates a Response with a 401 HTTP header and a given message.
     *
     * @param  string $message
     * @param  array  $headers
     * @return IlluminateResponse
     */
    protected function errorUnauthorized($message = 'Unauthorized', array $headers = [])
    {
        return $this->setStatusCode(Response::HTTP_UNAUTHORIZED)
                    ->respondWithError($message, self::CODE_UNAUTHORIZED, $headers);
    }

    /**
     * Generates a Response with a 400 HTTP header and a given message.
     *
     * @param  string $message
     * @param  array  $headers
     * @return IlluminateResponse
     */
    protected function errorWrongArgs($message = 'Wrong Arguments', array $headers = [])
    {
        return $this->setStatusCode(Response::HTTP_BAD_REQUEST)
                    ->respondWithError($message, self::CODE_WRONG_ARGS, $headers);
    }

}