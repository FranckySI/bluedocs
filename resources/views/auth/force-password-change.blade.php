<x-app-layout>
    <div class="mx-auto max-w-2xl space-y-6 px-4 py-10 sm:px-6 lg:px-8">
        <section class="rounded-2xl border border-amber-200 bg-amber-50 p-6">
            <h1 class="text-xl font-semibold text-slate-900">Changer le mot de passe provisoire</h1>
            <p class="mt-2 text-sm leading-6 text-slate-700">
                Pour protéger votre compte, vous devez remplacer le mot de passe communiqué par votre administrateur avant de continuer.
            </p>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <form method="POST" action="{{ route('password.first-login.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="current_password" value="Mot de passe provisoire" />
                    <x-text-input id="current_password" name="current_password" type="password"
                        class="mt-1 block w-full" autocomplete="current-password" required />
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password" value="Nouveau mot de passe" />
                    <x-text-input id="password" name="password" type="password"
                        class="mt-1 block w-full" autocomplete="new-password" required />
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" value="Confirmer le nouveau mot de passe" />
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                        class="mt-1 block w-full" autocomplete="new-password" required />
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                </div>

                <x-primary-button>Enregistrer le nouveau mot de passe</x-primary-button>
            </form>
        </section>
    </div>
</x-app-layout>
