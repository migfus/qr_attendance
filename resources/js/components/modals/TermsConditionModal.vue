<template>
    <TransitionRoot as="template" :show="$modal">
        <Dialog as="div" class="relative z-40" @close="$modal = false">
            <TransitionChild
                as="template"
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-dark-003/50 backdrop-blur-md" />
            </TransitionChild>

            <div class="fixed inset-0 z-50 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center text-center sm:items-center sm:p-0">
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <DialogPanel
                            class="relative transform overflow-hidden sm:rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 w-full sm:max-w-2xl"
                        >
                            <!-- NOTE: REMOVEAL  PROMPT -->
                            <div class="bg-white dark:bg-dark-002 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <!-- NOTE: ICON -->
                                    <div
                                        class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-brand-100 dark:bg-dark-001 sm:mx-0 sm:h-10 sm:w-10"
                                    >
                                        <InformationCircleIcon class="h-6 w-6 text-brand-600 dark:text-light-003" aria-hidden="true" />
                                    </div>
                                    <!-- NOTE: TITLE -->
                                    <div class="mt-3 sm:ml-4 sm:mt-0 sm:text-left">
                                        <DialogTitle as="h3" class="text-base font-semibold leading-6 text-gray-900 dark:text-neutral-200">
                                            Terms and Condition
                                        </DialogTitle>
                                        <!-- filepath: d:\@Projects\2025\Web\CMU Projects\OHRM QR\ohrm_qr\resources\js\components\modals\TermsConditionModal.vue -->
                                        <div class="mt-2 flex flex-col gap-2 text-sm text-brand-700 dark:text-neutral-300">
                                            <p>
                                                Welcome to Office of Human Resource Management (“we”, “us”, “our”). These Terms and Conditions (“Terms”) govern
                                                your use of our website and services, specifically for submitting and processing personal information to
                                                generate printable employee identification cards.
                                            </p>
                                            <p>
                                                By using this website, you agree to be bound by these Terms. If you do not agree, please do not use our
                                                services.
                                            </p>

                                            <p class="font-semibold mt-2">1. Use of the Service</p>
                                            <p>
                                                Our platform is designed to collect employee information (such as full name, photo, position, department, and
                                                other necessary data) for the purpose of generating and downloading printable ID cards.
                                            </p>
                                            <ul class="list-disc ml-6">
                                                <li>Use the service only for lawful, organizational purposes.</li>
                                                <li>Submit only accurate and authorized employee information.</li>
                                            </ul>

                                            <p class="font-semibold mt-2">2. Data Collection and Privacy</p>
                                            <p>
                                                We collect personal information solely for the purpose of generating ID cards. The types of data collected may
                                                include:
                                            </p>
                                            <ul class="list-disc ml-6">
                                                <li>Full name</li>
                                                <li>Position/Title</li>
                                                <li>Status</li>
                                                <li>Photo</li>
                                                <li>Department</li>
                                                <li>Contact information (email)</li>
                                            </ul>
                                            <p>By submitting this data, you confirm that:</p>
                                            <ul class="list-disc ml-6">
                                                <li>You have permission to submit the information (either your own or that of others).</li>
                                                <li>The information provided is true and accurate.</li>
                                            </ul>
                                            <p>Please refer to our Privacy Policy for full details on how we collect, store, and manage personal data.</p>

                                            <p class="font-semibold mt-2">3. Data Usage and Retention</p>
                                            <p>
                                                The submitted data will be used only for generating ID cards.<br />
                                                We do not sell, share, or use the data for any other purposes.<br />
                                                We may store the data temporarily to allow you to edit or re-download the ID cards, after which it may be
                                                deleted periodically.<br />
                                                If you wish to have your data removed, you may request deletion by contacting us at obm@cmu.edu.ph.
                                            </p>

                                            <p class="font-semibold mt-2">4. User Responsibilities</p>
                                            <ul class="list-disc ml-6">
                                                <li>Ensuring you have the right to upload any personal data.</li>
                                                <li>The consequences of uploading false or unauthorized data.</li>
                                                <li>Protecting your login credentials (if applicable).</li>
                                            </ul>

                                            <p class="font-semibold mt-2">5. Intellectual Property</p>
                                            <p>
                                                All designs, templates, and materials used in the ID card generator are the intellectual property of Central
                                                Mindanao University and may not be copied, resold, or reused without permission.
                                            </p>

                                            <p class="font-semibold mt-2">6. Disclaimer</p>
                                            <p>
                                                We do not guarantee uninterrupted or error-free operation of the service. The service is provided “as is” and
                                                “as available.”<br />
                                                We are not liable for:<br />
                                                - Any misuse of generated ID cards.<br />
                                                - Any loss or damage arising from the use of or reliance on our service.
                                            </p>

                                            <p class="font-semibold mt-2">7. Modifications to Terms</p>
                                            <p>
                                                We reserve the right to update or modify these Terms at any time. We will post the updated Terms on this page
                                                with a new effective date.
                                            </p>

                                            <p class="font-semibold mt-2">8. Governing Law</p>
                                            <p>These Terms shall be governed by and interpreted in accordance with the laws of the Philippines.</p>

                                            <p class="font-semibold mt-2">9. Contact Us</p>
                                            <p>
                                                If you have any questions or concerns about these Terms, please contact us at:<br />
                                                Email: hrmo@cmu.edu.ph<br />
                                                Company: Office of Human Resource Management<br />
                                                Address: Admin Extension, Central Mindanao University, Musuan, Maramag, Bukidnon
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- NOTE: ACTIONS BUTTON -->
                            <div class="bg-gray-50 dark:bg-dark-002 px-4 py-3 flex flex-col sm:flex-row gap-2 justify-end">
                                <AppButton @click="agree()" color="brand" :icon="CheckIcon"> Agree </AppButton>
                                <AppButton @click="disagree()" :icon="XMarkIcon">Disagree</AppButton>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup lang="ts">
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { ExclamationTriangleIcon } from '@heroicons/vue/24/solid'
import AppButton from '@/components/form/AppButton.vue'
import { CheckIcon, InformationCircleIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const $modal = defineModel<boolean>()
const $emit = defineEmits(['confirm', 'disagree'])

function agree() {
    $modal.value = false
    $emit('confirm')
}

function disagree() {
    $modal.value = false
    $emit('disagree')
}
</script>
