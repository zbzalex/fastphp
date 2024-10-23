<?php

namespace fastphp;

class RequestHandler
{
    private $matcher;
    private $dispatcher;

    public function __construct(
        RouteMatcher $matcher,
        EventDispatcher $dispatcher
    ) {
        $this->matcher = $matcher;
        $this->dispatcher = $dispatcher;
    }

    public function handle(Request $request)
    {
        $response = null;
        $route = null;

        try {

            $route = $this->matcher->match($request);
            if ($route === null)
                throw new HttpException("Not Found", 404);

            $controller = $route->getController();
            $event = new GetResponseEvent($request);
            $this->dispatcher->dispatch(LifecycleEvents::REQUEST, $event);
            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            $response = call_user_func_array($controller, [
                $request,
            ]);

            if (is_array($response)) {
                $response = new JsonResponse($response);
            } else if (is_scalar($response)) {
                $response = new Response($response);
            }

            $event = new FilterResponseEvent($request, $response);
            $this->dispatcher->dispatch(LifecycleEvents::RESPONSE, $event);

            if (!$event->hasResponse())
                throw new \Exception(
                    sprintf(
                        "Are your remember to send response?"
                    )
                );

            return $event->getResponse();
        } catch (\Exception $e) {

            $event = new GetResponseForExceptionEvent($e, $request);
            $this->dispatcher->dispatch(LifecycleEvents::EXCEPTION, $event);

            if ($event->hasResponse()) {
                return $event->getResponse();
            }
        }

        return null;
    }
}
