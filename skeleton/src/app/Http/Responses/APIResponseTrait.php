<?php
namespace BetterFly\Skeleton\App\Http\Responses;

use Illuminate\Database\Eloquent\Model;

trait APIResponseTrait
{
    public $SUCCESS_STATUS = 200;
    public $NOT_AUTHORIZED = 401;

  /**
   * @param mixed $data
   * @param int $status
   * @param array $headers
   * @param int $options
   * @return \Illuminate\Http\JsonResponse
   */
  public function responseWithData($data, int $status = 200, array $headers = array(), int $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
  {
    if($data instanceof Model) {
      $data = $data->toArray();
    }

    return $this->response([
      'data' => $data
    ], $status, $headers, $options);
  }

    public function responseWithDataAndMessage($data, int $status = 200, string $message = null,
                                               array $headers = array(), int $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    {
        if($data instanceof Model) {
            $data = $data->toArray();
        }

        return $this->response([
            'data' => $data,
            'message' => $message,
            'status' => $status
        ], $status, $headers, $options);
    }

  /**
   * @param array|string $error
   * @param int $status
   * @param array $headers
   * @param int $options
   * @return \Illuminate\Http\JsonResponse
   */
  public function responseWithError($error, int $status = 500, array $headers = array(), int $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
  {
    if (!is_array($error)) {
      $error = ['message' => $error];
    }

    return $this->responseWithErrors([$error], $status, $headers, $options);
  }

  /**
   * @param array $errors
   * @param int $status
   * @param array $headers
   * @param int $options
   * @return \Illuminate\Http\JsonResponse
   */
  private function responseWithErrors(array $errors, int $status = 500, array $headers = array(), int $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
  {
    return $this->response([
      'http' => [
        'status' => $status
      ],
      'errors' => $errors
    ], $status, $headers, $options);
  }

  /**
   * @param array $data
   * @param int $status
   * @param array $headers
   * @param int $options
   * @return \Illuminate\Http\JsonResponse
   */
  public function response(array $data, int $status, array $headers = array(), int $options = 0)
  {
    return \Response::json($data, $status, $headers, $options);
  }
}