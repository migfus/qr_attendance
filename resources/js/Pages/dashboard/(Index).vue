<template>
    <div class="flex flex-col gap-4 dark:bg-dark-001">
        <Unedited />
        <div class="flex flex-col sm:grid grid-cols-2 xl:grid-cols-4 gap-4">
            <StatisticCard label="Storage" :icon="CircleStackIcon" :current_number="storage.free" sub_label="free" />
            <StatisticCard label="Users" :icon="UsersIcon" :current_number="total_users" sub_label="total" />
            <StatisticCard label="Employees' Request" :icon="CreditCardIcon" :current_number="total_employees" sub_label="total" />
            <StatisticCard label="QR" :icon="QrCodeIcon" :current_number="total_qr" sub_label="total" />
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
            <div class="flex flex-col">
                <BasicCard title="Newly Request" :icon="ArrowRightIcon" description="List of newly request.">
                    <DataTransition class="flex flex-col dark:bg-dark-002 rounded-xl space-y-1">
                        <div
                            v-for="(item, idx) in new_employees"
                            class="flex justify-between gap-1 items-center group dark:bg-dark-001 bg-light-002 hover:bg-light-003 rounded-xl object-shadow p-2 px-4 hover:dark:bg-dark-003"
                            :style="loading_row_data ? { transitionDelay: `${idx * 20}ms` } : {}"
                        >
                            <label class="line-clamp-1 dark:text-neutral-300 text-sm">{{ item.name }}</label>
                            <AppButton
                                :icon="ArrowRightIcon"
                                size="sm"
                                :iconOnly="true"
                                color="brand"
                                :href="route('dashboard.arta-id.show', { arta_id: item.id })"
                                class="pr-0 opacity-0 transition-all group-hover:opacity-100"
                            />
                        </div>
                    </DataTransition>
                </BasicCard>
            </div>

            <div class="flex flex-col">
                <BasicCard title="Created Department" :icon="ArrowRightIcon" description="List of newly request.">
                    <DataTransition class="flex flex-col gap-1 rounded-xl">
                        <div
                            v-for="(item, idx) in new_departments_filtered"
                            class="flex justify-between gap-1 items-center group bg-light-002 dark:bg-dark-001 hover:bg-light-003 rounded-xl object-shadow p-2 px-4 hover:dark:bg-dark-003"
                            :style="loading_row_data ? { transitionDelay: `${idx * 20}ms` } : {}"
                        >
                            <label class="line-clamp-1 dark:text-neutral-300 text-sm">{{ item.name }}</label>
                            <AppButton
                                :icon="ArrowRightIcon"
                                color="brand"
                                size="sm"
                                :iconOnly="true"
                                :href="route('dashboard.departments.edit', { department: item.id })"
                                class="pr-0 opacity-0 transition-all group-hover:opacity-100"
                            />
                        </div>
                    </DataTransition>
                </BasicCard>
            </div>

            <div class="flex flex-col">
                <BasicCard title="Weekly Activity" :icon="ChartBarIcon" class="h-auto" description="Weekly amount tracking">
                    <Line :data="chartData" :options="chartOptions" />
                </BasicCard>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import { Line } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement, ArcElement } from 'chart.js'
import { ArrowRightIcon, ChartBarIcon, CircleStackIcon, CreditCardIcon, QrCodeIcon, UsersIcon } from '@heroicons/vue/24/outline'
import StatisticCard from '@/components/cards/StatisticCard.vue'
import AppButton from '@/components/form/AppButton.vue'
import DataTransition from '@/components/transitions/DataTransition.vue'
import Unedited from '@/components/test/Unedited.vue'

import { SimpleDataTable } from '@/globalTypes'
import { Department } from './departments/departmentInt'
import { Employee } from './arta-id/artaIdInt'

import { employeeFullName, errorAlert } from '@/utils'
import { ref, computed } from 'vue'
import { onErrorCaptured } from 'vue'
import { usePreferredColorScheme } from '@vueuse/core'

// Define props
const $props = defineProps<{
    stat_data: {
        registrations: {
            labels: string[]
            datasets: {
                label: string
                background: string
                data: number[]
            }[]
        }
        top_departments: {
            labels: string[]
            datasets: {
                label: string
                background: string
                data: number[]
            }[]
        }
    }
    index_data: Employee[]
    new_departments: Department[]
    storage: {
        total: number
        used: number
        free: number
    }
    total_users: number
    total_employees: number
    total_qr: number
}>()

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement, ArcElement)

// Detect dark mode
// const isDarkMode = ref(localStorage.getItem('theme') == 'Dark' || localStorage.getItem('theme') == 'dark')
const isDarkMode = ref('dark' === usePreferredColorScheme().value)
const new_employees = ref<SimpleDataTable[]>(
    $props.index_data.map((employee) => ({
        id: employee.id,
        name: employeeFullName(employee, true),
        status: employee.latest_request_status ? employee.latest_request_status.request_status_type.name : 'N/A'
    }))
)
const new_departments_filtered = ref<SimpleDataTable[]>(
    $props.new_departments.map((department) => ({
        id: department.id.toString(),
        name: department.name,
        status: department.employees_count.toString()
    }))
)

// Define dynamic chart options
const chartOptions = computed(() => ({
    responsive: true,
    plugins: {
        legend: {
            labels: {
                color: isDarkMode.value ? '#ffffff' : '#333333' // White text in dark mode, dark in light mode
            }
        }
    },
    scales: {
        x: {
            ticks: {
                color: isDarkMode.value ? '#ffffff' : '#333333'
            },
            grid: {
                color: isDarkMode.value ? 'rgba(239, 68, 68, 0.2)' : 'rgba(0, 0, 0, 0.1)'
            }
        },
        y: {
            ticks: {
                color: isDarkMode.value ? '#ffffff' : '#333333'
            },
            grid: {
                color: isDarkMode.value ? 'rgba(255, 255, 255, 0.2)' : 'rgba(0, 0, 0, 0.1)'
            }
        }
    }
}))

// Update dataset colors dynamically for dark mode
const chartData = computed(() => {
    return {
        labels: $props.stat_data.registrations.labels,
        datasets: $props.stat_data.registrations.datasets.map((dataset) => ({
            ...dataset,
            borderColor: ['#ef4444', '#5b715e'], // Green in dark mode, blue in light mode
            backgroundColor: isDarkMode.value ? 'rgba(76, 175, 80, 0.2)' : 'rgba(30, 64, 175, 0.2)', // Adjust fill color
            pointBackgroundColor: isDarkMode.value ? '#4CAF50' : '#1E40AF', // Adjust data points
            pointBorderColor: isDarkMode.value ? '#ffffff' : '#000'
        }))
    }
})

onErrorCaptured((e) => errorAlert('/dashboard/index/(Index)', e))

const loading_row_data = ref(true)
setTimeout(() => {
    loading_row_data.value = false
}, $props.index_data.length * 15)
</script>
