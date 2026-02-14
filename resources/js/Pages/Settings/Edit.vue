<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
	settings: Object,
});

// Инициализируем форму. Если в базе уже есть ссылка, она подставится автоматически
const form = useForm({
	url: props.settings?.url ?? '',
});

const submit = () => {
	form.patch(route('settings.update'), {
		preserveScroll: true,
		onSuccess: () => alert('Ссылка сохранена!'),
	});
};
</script>

<template>
	<Head title="Настройки Яндекс Карт" />

	<AuthenticatedLayout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">Подключение площадки</h2>
		</template>

		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
					<form @submit.prevent="submit" class="space-y-6 max-w-xl">
						<div>
							<InputLabel for="url" value="Укажите ссылку на Яндекс.Карты" />

							<TextInput
									id="url"
									type="url"
									class="mt-1 block w-full"
									v-model="form.url"
									required
									placeholder="https://yandex.ru/maps/org/..."
							/>

							<InputError class="mt-2" :message="form.errors.url" />
						</div>

						<div class="flex items-center gap-4">
							<PrimaryButton :disabled="form.processing">Сохранить</PrimaryButton>

							<Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
								<p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Сохранено.</p>
							</Transition>
						</div>
					</form>
				</div>
			</div>
		</div>
	</AuthenticatedLayout>
</template>