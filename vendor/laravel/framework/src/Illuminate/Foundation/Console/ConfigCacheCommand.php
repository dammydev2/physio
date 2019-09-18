     *
     * @return array
     */
    protected function context()
    {
        try {
            return array_filter([
                'userId' => Auth::id(),
                // 'email' => Auth::user() ? Auth::user()->email : null,
            ]);
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Render an exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $e)
    {
        if (method_exists($e, 'render') && $response = $e->render($request)) {
            return Router::toResponse($request, $response);
        } elseif ($e instanceof Responsable) {
            return $e->toResponse($request);
        }

        $e = $this->prepareException($e);

        if ($e instanceof HttpResponseException) {
            return $e->getResponse();
        } elseif ($e instanceof AuthenticationException) {
            return $this->unauthenticated($request, $e);
        } elseif ($e instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($e, $request);
        }

        return $request->expectsJson()
                        ? $this->prepareJsonResponse($request, $e)
                        : $this->prepareResponse($request, $e);
    }

    /**
     * Prepare exception for rendering.
     *
     * @param  \Exception  $e
     * @return \Exception
     */
    protected function prepareException(Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        } elseif ($e instanceof AuthorizationException) {
            $e = new AccessDeniedHttpException($e->getMessage(), $e);
        } elseif ($e instanceof TokenMismatchException) {
            $e = new HttpException(419, $e->getMessage(), $e);
        }

        return $e;
    }

    /**
     * Convert an authentication exception into a 