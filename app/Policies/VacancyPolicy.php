<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Auth\Access\HandlesAuthorization;

class VacancyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {

        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Vacancy $vacancy
     * @return mixed
     */
    public function view(User $user, Vacancy $vacancy)
    {
        if ($user->role === User::ROLE_ADMIN or $user->role === User::ROLE_WORKER or $user->id === $vacancy->employer_id) {
            return true;
            //просто поле подписчиков для рабочего не отображать
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->role === User::ROLE_ADMIN or $user->role === User::ROLE_EMPLOYER and $user->organizations()->where('id', request()->organization_id)->exists()) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Vacancy $vacancy
     * @return mixed
     */
    public function update(User $user, Vacancy $vacancy)
    {
        if ($user->role === User::ROLE_ADMIN or $user->id === $vacancy->employer_id and $user->role === User::ROLE_EMPLOYER) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Vacancy $vacancy
     * @return mixed
     */
    public function delete(User $user, Vacancy $vacancy)
    {
        if ($user->role === User::ROLE_ADMIN or $user->id === $vacancy->employer_id) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Vacancy $vacancy
     * @return mixed
     */
    public function restore(User $user, Vacancy $vacancy)
    {
        if ($user->role === User::ROLE_ADMIN) {
            return true;
        }
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Vacancy $vacancy
     * @return mixed
     */
    //public function forceDelete(User $user, Vacancy $vacancy)
    // {
    //  return false;
    // }

    public function vacancy_book(User $user, Vacancy $vacancy)
    {
        if ($user->role === User::ROLE_WORKER) {
            return true;
        }
    }

    public function vacancy_unbook(User $user, Vacancy $vacancy)
    {

        if (($user->role === User::ROLE_EMPLOYER and $user->id === $vacancy->employer_id)
            or ($user->role === User::ROLE_WORKER and $user->id === request()->user_id)
        ) {
            return true;
        }
    }

    public function vacancies_count(User $user)
    {
        if ($user->role === User::ROLE_ADMIN) {
            return true;
        }
    }

}
