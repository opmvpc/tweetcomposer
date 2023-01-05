<?php

namespace App\Policies;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ThreadPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function viewAny(User $user)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function view(User $user, Thread $thread)
    {
        return $user->id === $thread->user_id
                        ? Response::allow()
                        : Response::deny('You do not own this thread.');
    }

    /**
     * Determine whether the user can create models.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function create(User $user)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function update(User $user, Thread $thread)
    {
        return $user->id === $thread->user_id
                                ? Response::allow()
                                : Response::deny('You do not own this thread.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function delete(User $user, Thread $thread)
    {
        return $user->id === $thread->user_id
                                ? Response::allow()
                                : Response::deny('You do not own this thread.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function restore(User $user, Thread $thread)
    {
        return $user->id === $thread->user_id
                                        ? Response::allow()
                                        : Response::deny('You do not own this thread.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function forceDelete(User $user, Thread $thread)
    {
        return $user->id === $thread->user_id
                                        ? Response::allow()
                                        : Response::deny('You do not own this thread.');
    }
}
