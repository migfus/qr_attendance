<template>
    <div class="flex flex-col relative w-full dark:bg-dark-001 gap-4">
        <Unedited />
        <div class="flex flex-col xl:grid grid-cols-4 gap-4">
            <!-- SECTION LAYERS -->
            <div class="flex flex-col gap-4">
                <LayersCard
                    :removing_background
                    :layers
                    :selected_layer
                    @selectLayer="selectLayer"
                    @moveBackward="moveBackward"
                    @moveForward="moveForward"
                    @showImportModal="show_import_modal = true"
                />

                <FilesCard
                    :files="show_data.files"
                    :selected_file
                    @importImage="importImage"
                    @selectFile="(item: File) => (selected_file = item)"
                    v-model:selected_text_value="selected_text_value"
                />
            </div>

            <!-- SECTION CONTENT -->
            <div class="flex flex-col col-span-2">
                <div class="bg-white rounded-lg border border-brand-50 dark:border-gray-700" ref="canvasContainerRef">
                    <canvas id="canvasId" ref="canvasRef" />
                    <div v-if="removing_background" ref="aiLoading" class="absolute bg-brand-800/50 top-0 h-full w-[630px] flex justify-center items-center">
                        <div class="font-semibold text-lg animate-pulse flex flex-col items-center text-white">
                            <SparklesIcon class="size-8" />
                            <p>Removing Background...</p>
                            <p v-if="show_additional_message" class="text-white">{{ show_additional_message }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION TOOLS -->
            <div class="flex flex-col gap-4">
                <ToolsCard
                    :selected_layer
                    :id="show_data.id"
                    :removing_background
                    v-model:selected_text_value="selected_text_value"
                    v-model:selected_x="selected_x"
                    v-model:selected_y="selected_y"
                    v-model:selected_angle="selected_angle"
                    v-model:selected_height="selected_height"
                    v-model:selected_width="selected_width"
                    @updateObjectSize="updateObjectSize"
                    @deleteLayer="deleteLayer"
                    @updateTextValue="updateTextValue"
                    @toggleBold="toggleBold"
                    @toggleItalic="toggleItalic"
                    @flipHorizontal="flipHorizontal()"
                    @flipVertical="flipVertical()"
                    @removeBg="removeBg()"
                    @exportAsJson="exportAsJson()"
                    @exportAsJpg="exportAsJpg()"
                    @exportAsSvg="exportAsSvg()"
                    @saveToOnlineFile="saveToOnlineFile()"
                    @openToPhotopea="openToPhotopea()"
                />

                <BasicCard title="QR Code" description="QR Code" :icon="QrCodeIcon" ref="parentQr">
                    <div class="flex flex-col justify-between gap-2 h-full w-full bg-transparent" ref="qrCodeRef">
                        <!-- <QRCodeVue3
                            :value="`${$page.props.base_url}/qr/${show_data.quick_response_codes[0].id}`"
                            :width="290"
                            :height="290"
                            :image-options="{
                                hideBackgroundDots: false,
                                imageSize: 0.4,
                                margin: 10
                            }"
                            :corners-square-options="{ type: 'square', color: '#000' }"
                            :dots-options="{
                                type: 'square',
                                color: '#000'
                            }"
                            :qrOptions="{ errorCorrectionLevel: 'H' }"
                            :background-options="{ color: 'transparent' }"
                        >
                        </QRCodeVue3> -->

                        <QrcodeVue
                            :value="`${$page.props.base_url}/qr/${show_data.quick_response_codes[0].id}`"
                            :size="qr_size"
                            :margin="1"
                            level="H"
                            renderAs="svg"
                            background="#dcdfda"
                        />
                    </div>
                </BasicCard>

                <ShortcutCard />
            </div>
        </div>

        <UploadFileModal
            v-model="show_import_modal"
            title="Import JSON"
            id="import_json"
            :confirmIcon="ArrowUpIcon"
            confirmMessage="Import"
            @confirm="loadCanvasFromJSON()"
        >
            <div class="flex flex-col p-4">
                <input type="file" @change="handleFileUpload" accept=".json" class="bg-dark-001 p-4 rounded-xl hover:bg-dark-002 cursor-pointer w-full" />
            </div>
        </UploadFileModal>
    </div>
</template>

<script setup lang="ts">
import { Select, File } from '@/globalTypes'
import { Employee } from '../artaIdInt'
import { onMounted, ref, toRaw, watch, useTemplateRef } from 'vue'
import BasicCard from '@/components/cards/BasicCard.vue'
import * as fabric from 'fabric'
import { employeeFullName } from '@/utils'
import UploadFileModal from '@/components/modals/UploadFileModal.vue'
import LayersCard from './LayersCard.vue'
import FilesCard from './FilesCard.vue'
import ShortcutCard from './ShortcutCard.vue'
import ToolsCard from './ToolsCard.vue'
import { ArrowUpIcon, SparklesIcon, QrCodeIcon } from '@heroicons/vue/24/outline'
import { removeBackground } from '@imgly/background-removal'
import { router, usePage } from '@inertiajs/vue3'
import { toPng } from 'html-to-image'
import QrcodeVue from 'qrcode.vue'
import Unedited from '@/components/test/Unedited.vue'

interface Layer extends fabric.FabricObject {
    layerName: string
    type: string
}

const { show_data } = defineProps<{
    show_data: Employee
    request_status_types: Select[]
}>()

const canvas_container_ref = useTemplateRef('canvasContainerRef')

const selected_layer = ref<any>(null)
const selected_width = ref<number>(0)
const selected_height = ref<number>(0)
const selected_angle = ref<number>(0)
const selected_x = ref<number>(0)
const selected_y = ref<number>(0)
const selected_text_value = ref<string>('')
const layers = ref<any[]>([])
const removing_background = ref<boolean>(false)
const show_additional_message = ref<string>('')
let canvas: fabric.Canvas | null = null
const snap_threshold = 100
const show_import_modal = ref(false)
const json_string = ref<string>('')
const $page = usePage()
const qr_code_ref = useTemplateRef<HTMLElement>('qrCodeRef')
const selected_file = ref<File | null>(null)
const parent_qr = useTemplateRef<HTMLElement>('parentQr')
const qr_size = ref(300)

function loadFont(fontName: string, fontUrl: string): Promise<void> {
    return new Promise((resolve, reject) => {
        const font = new FontFace(fontName, `url(${fontUrl})`)
        font.load()
            .then((loadedFont) => {
                document.fonts.add(loadedFont)
                resolve()
            })
            .catch(reject)
    })
}

function zoomToFit() {
    if (canvas_container_ref.value == null) return
    if (!canvas) return

    const containerWidth = canvas_container_ref.value.offsetWidth
    const containerHeight = canvas_container_ref.value.offsetHeight

    const canvasWidth = 1200
    const canvasHeight = 1800

    // Calculate the scale factor to fit the canvas content
    const scaleX = containerWidth / canvasWidth
    const scaleY = containerHeight / canvasHeight
    const scale = Math.min(scaleX, scaleY)

    if (canvas) {
        canvas?.setZoom(scale)

        canvas?.setWidth(canvasWidth * scale)
        canvas?.setHeight(canvasHeight * scale)
        canvas?.renderAll()
    }
}

function updateObjectSize() {
    if (selected_layer.value) {
        selected_layer.value.set({
            width: Math.floor(selected_width.value),
            height: Math.floor(selected_height.value),
            left: Math.floor(selected_x.value),
            top: Math.floor(selected_y.value),
            scaleX: 1, // Reset scaling to avoid conflicts
            scaleY: 1,
            angle: Math.floor(selected_angle.value)
        })
        selected_layer.value.setCoords() // Update object boundaries
        canvas?.renderAll()
    }
}

function deleteLayer() {
    if (canvas?.getActiveObject()) {
        canvas?.remove(toRaw<fabric.FabricObject>(selected_layer.value)) // Remove the selected object
        refreshLayers()
    } else {
        alert('no layer selected')
    }
}

function updateTextValue() {
    if (selected_layer.value && selected_layer.value.type === 'text') {
        selected_layer.value.set('text', selected_text_value.value) // Update the text property
        selected_layer.value.set('layerName', selected_text_value.value) // Update the layer name
        canvas?.renderAll() // Re-render the canvas
    }
}

function selectLayer(layer: fabric.FabricObject) {
    if (!canvas) {
        alert('no layer selected')
        return
    }

    selected_layer.value = layer // Get the selected object
    selected_width.value = layer.width! * layer.scaleX! // Get actual width
    selected_height.value = layer.height! * layer.scaleY! // Get actual height

    selected_x.value = layer.left! // Get actual x position
    selected_y.value = layer.top! // Get actual y position
    selected_angle.value = layer.angle // Get actual angle
    // selected_crop_x.value = 0
    // selected_crop_y.value = 0
    if (layer.type === 'text') {
        // @ts-ignore
        selected_text_value.value = layer.text // Get actual text value
    }

    canvas?.setActiveObject(toRaw(layer)) // Set the selected layer as active

    // canvas?.renderAll() // Re-render the canvas
    refreshLayers()
}

function resetLayer() {
    selected_layer.value = null
    selected_width.value = 0
    selected_height.value = 0

    selected_x.value = 0
    selected_y.value = 0
    // selected_crop_x.value = 0
    // selected_crop_y.value = 0
    selected_angle.value = 0
    selected_text_value.value = ''
}

async function removeBg() {
    if (!selected_layer.value || selected_layer.value.type !== 'image') {
        alert('Please select an image layer to remove the background.')
        return
    }

    removing_background.value = true

    const originalUrl = toRaw(selected_layer.value.getSrc()) // Get the URL of the selected image layer

    try {
        const blob = await removeBackground(originalUrl) // Remove background using the provided function
        const reader = new FileReader()

        reader.onload = async (event) => {
            const base64Image = event.target?.result

            const bg_removed_image = await fabric.FabricImage.fromURL(
                base64Image as string,
                {
                    crossOrigin: 'anonymous', // Optional: Set cross-origin for CORS
                    signal: new AbortController().signal // Optional: Abort signal for cancellation
                },
                {
                    left: selected_layer.value?.left, // X position
                    top: selected_layer.value?.top, // Y position
                    scaleX: 1, // Horizontal scaling
                    scaleY: 1, // Vertical scaling
                    selectable: true, // Allow selection
                    evented: true, // Enable mouse events
                    hasControls: true, // Show resize and rotate controls
                    lockScalingX: false, // Allow horizontal scaling
                    lockScalingY: false, // Allow vertical scaling
                    lockRotation: false, // Allow rotation
                    originX: 'center', // Horizontal origin
                    originY: 'center', // Vertical origin
                    layerName: `${selected_layer.value?.layerName} - no bg`, // Custom layer name,
                    id: `layer-${Date.now()}`,
                    shadow: new fabric.Shadow({
                        color: 'rgba(0, 0, 0, 0.5)',
                        blur: 10,
                        offsetX: 5,
                        offsetY: 5,
                        affectStroke: false,
                        includeDefaultValues: false,
                        nonScaling: false
                    })
                }
            )

            canvas?.remove(toRaw(selected_layer.value)) // Remove the old image
            canvas?.add(bg_removed_image) // Add the new image

            selected_layer.value = bg_removed_image // Update the selected layer
            // canvas?.sendObjectToBack(bg_removed_image) // Send the new image to the back

            refreshLayers()
        }

        reader.readAsDataURL(blob) // Convert the blob to a base64 string
    } catch (error) {
        console.error('Error removing background:', error)
        alert('Failed to remove background. Please try again.')
    } finally {
        removing_background.value = false
    }
}

function moveBackward(item: fabric.FabricObject) {
    if (!canvas) {
        return
    }
    canvas?.sendObjectBackwards(toRaw(item)) // Move the selected object one step back
    selected_layer.value = null
}

function moveForward(item: fabric.FabricObject) {
    if (!canvas) {
        return
    }
    canvas?.bringObjectForward(toRaw(item)) // Move the selected object one step back
    selected_layer.value = null
}

function exportAsJpg() {
    if (!canvas) {
        alert('Canvas is not initialized.')
        return
    }

    // Convert the canvas to a data URL in JPG format
    const dataURL = canvas.toDataURL({
        format: 'jpeg',
        quality: 1, // Adjust quality (0.1 to 1.0),
        multiplier: 3 // Adjust multiplier for higher resolution
    })

    // Create a temporary link element to trigger the download
    const link = document.createElement('a')
    link.href = dataURL
    link.download = 'canvas-image.jpg' // Set the file name
    link.click()
}

function exportAsSvg() {
    if (!canvas) {
        alert('Canvas is not initialized.')
        return
    }

    // Convert the canvas to an SVG string

    const svgData = canvas.toSVG()

    // Create a temporary link element to trigger the download
    const blob = new Blob([svgData], { type: 'image/svg+xml;charset=utf-8' })
    const link = document.createElement('a')
    link.href = URL.createObjectURL(blob)
    link.download = 'canvas-image.svg' // Set the file name
    link.click()

    // Clean up the object URL
    URL.revokeObjectURL(link.href)
}

function exportAsJson() {
    if (!canvas) {
        alert('Canvas is not initialized.')
        return
    }

    // Serialize the canvas to JSON
    const canvasJSON = canvas.toJSON()

    // Convert the JSON object to a string
    const jsonString = JSON.stringify(canvasJSON)
    console.log('jsonString', jsonString)

    // Create a temporary link to download the JSON file
    const blob = new Blob([jsonString], { type: 'application/json' })
    const link = document.createElement('a')
    link.href = URL.createObjectURL(blob)
    link.download = `${employeeFullName(show_data)} - canvas-project.json` // Set the file name
    link.click()

    // Clean up the object URL
    URL.revokeObjectURL(link.href)
}

function handleFileUpload(event: Event) {
    const file = (event.target as HTMLInputElement).files?.[0]
    if (!file) return

    const reader = new FileReader()
    reader.onload = (e) => {
        json_string.value = e.target?.result as string
    }
    reader.readAsText(file)
}

async function loadCanvasFromJSON() {
    if (!canvas) {
        alert('Canvas is not initialized.')
        return
    }
    if (json_string.value == '') {
        alert('Please upload a JSON file first.')
        return
    }

    // I want to load from json that includes "layerName" & "text" object properties
    await canvas.loadFromJSON(json_string.value, () => {
        refreshLayers() // Refresh the layers list
    })

    refreshLayers()
}

function showAdditionalMessage() {
    const messages = ['This may take a few moments. 😑', 'Just a little bit longer... 🥺']
    let counter = 0
    const interval = setInterval(() => {
        if (!removing_background.value) {
            clearInterval(interval) // Stop the loop when removing_background is false
            show_additional_message.value = ''
            return
        }
        show_additional_message.value = messages[counter % messages.length]
        counter++
    }, 5000)
}

async function addImageLayer(
    url: string,
    options: { top: number; left: number; selectable: boolean; hasControl: boolean; layerName: string; sendObjectToBack: boolean; sendObjectBackwards: boolean }
) {
    const image = await fabric.FabricImage.fromURL(
        url,
        {
            crossOrigin: 'anonymous', // Optional: Set cross-origin for CORS
            signal: new AbortController().signal // Optional: Abort signal for cancellation
        },
        {
            left: options.left, // X position
            top: options.top, // Y position
            scaleX: 1, // Horizontal scaling
            scaleY: 1, // Vertical scaling
            selectable: options.selectable, // Allow selection
            evented: true, // Enable mouse events
            hasControls: options.hasControl, // Show resize and rotate controls
            lockScalingX: false, // Allow horizontal scaling
            lockScalingY: false, // Allow vertical scaling
            lockRotation: false, // Allow rotation
            originX: 'center', // Horizontal origin
            originY: 'center', // Vertical origin
            layerName: options.layerName // Custom layer name,
        }
    )

    if (canvas) {
        canvas.add(image)
        if (options.sendObjectToBack) {
            canvas.sendObjectToBack(image) // Send the image to the back
        }
        if (options.sendObjectBackwards) {
            canvas.sendObjectBackwards(image) // Send the image backwards
        }
    }
}

function refreshLayers() {
    if (canvas) {
        layers.value = []
        layers.value = canvas
            .getObjects()
            .reverse()
            .map((item) => toRaw(item)) // Update the layers list
        canvas.renderAll() // Re-render the canvas
    }
}

function initTextLayers() {
    // const
    // NOTE: EMPLOYEE NAME1
    const employee_name = new fabric.FabricText(employeeFullName(show_data, false), {
        top: 1118,
        left: 1200 / 2,
        fill: 'black',
        fontSize: 70,
        textAlign: 'center',
        originX: 'center',
        originY: 'center',
        fontWeight: 'bold',
        fontFamily: 'Open Sans',
        layerName: employeeFullName(show_data)
    })
    canvas?.add(employee_name)

    // NOTE: EMPLOYEE POSITION
    const position = new fabric.FabricText(show_data.position.name.toUpperCase(), {
        top: 1250,
        left: 1200 / 2,
        fill: 'black',
        fontSize: 70,
        textAlign: 'center',
        originX: 'center',
        originY: 'center',
        fontWeight: 'bold',
        fontFamily: 'Open Sans',
        layerName: show_data.position.name
    })
    canvas?.add(position)

    // NOTE: EMPLOYEE POSITION
    const designation = new fabric.FabricText(show_data.department.name.toUpperCase(), {
        top: 1320,
        left: 1200 / 2,
        fill: 'black',
        fontSize: 40,
        textAlign: 'center',
        originX: 'center',
        originY: 'center',
        fontWeight: 'bold',
        fontFamily: 'Open Sans',
        layerName: `${show_data.department.name}`
    })
    canvas?.add(designation)
}

async function initImageLayers() {
    // NOTE: TEMPLATE
    await addImageLayer(`${$page.props.base_url}/images/template-new-2025-06-18.jpg`, {
        top: 1800 / 2,
        left: 1200 / 2,
        selectable: false,
        hasControl: false,
        layerName: 'Template',
        sendObjectToBack: false,
        sendObjectBackwards: false
    })

    // NOTE: EMPLOYEE IMAGE
    await addImageLayer(`${$page.props.base_url}${show_data.files[show_data.files.length - 2].file_url}`, {
        top: 800,
        left: 1200 / 2,
        selectable: true,
        hasControl: true,
        layerName: `${employeeFullName(show_data)}`,
        sendObjectToBack: false,
        sendObjectBackwards: false
    })

    if (qr_code_ref.value) {
        const qrData = await toPng(qr_code_ref.value, { cacheBust: true, backgroundColor: undefined })

        await addImageLayer(qrData, {
            top: 1477,
            left: 1200 / 2,
            selectable: true,
            hasControl: true,
            layerName: `QR Code`,
            sendObjectToBack: false,
            sendObjectBackwards: false
        })
    }
}

function fabricTriggerFunctions() {
    if (!canvas) {
        return
    }

    canvas.on('selection:created', (e) => {
        selected_layer.value = e.selected[0] // Get the selected object
        selected_width.value = parseFloat((selected_layer.value.width! * selected_layer.value.scaleX!).toFixed(2))
        selected_height.value = parseFloat((selected_layer.value.height! * selected_layer.value.scaleY!).toFixed(2))

        selected_x.value = parseFloat(selected_layer.value.left!.toFixed(2))
        selected_y.value = parseFloat(selected_layer.value.top!.toFixed(2))
        selected_angle.value = parseFloat(selected_layer.value.angle.toFixed(2))

        if (selected_layer.value.type === 'text') {
            selected_text_value.value = selected_layer.value.text // Get actual text value
        }
    })

    canvas.on('selection:updated', (e) => {
        selectLayer(e.selected[0]) // Get the selected object
    })

    canvas.on('object:moving', (e) => {
        selected_x.value = parseFloat(selected_layer.value.left!.toFixed(2))
        selected_y.value = parseFloat(selected_layer.value.top!.toFixed(2))
    })

    canvas.on('object:scaling', (e) => {
        selected_angle.value = selected_layer.value.angle
        selected_width.value = parseFloat((selected_layer.value.width! * selected_layer.value.scaleX!).toFixed(2))
        selected_height.value = parseFloat((selected_layer.value.height! * selected_layer.value.scaleY!).toFixed(2))
    })

    canvas.on('object:rotating', (e) => {
        selected_angle.value = parseFloat(selected_layer.value.angle.toFixed(2))
    })

    canvas.on('selection:cleared', () => {
        resetLayer()
    })
}

function keyboardTriggers() {
    document.addEventListener('keydown', (event) => {
        if (!canvas) return
        if (!selected_layer.value && event.key !== 'o') return

        // Prevent default action only if the event is not targeting an input, textarea, or contenteditable element
        const target = event.target as HTMLElement
        if (['INPUT', 'TEXTAREA'].includes(target.tagName) || target.isContentEditable) {
            return
        }

        event.preventDefault() // Prevent any default action

        const step = 2 // Movement step in pixels
        switch (event.key) {
            case 'ArrowUp':
                selected_layer.value.top! -= step
                break
            case 'ArrowDown':
                selected_layer.value.top! += step
                break
            case 'ArrowLeft':
                selected_layer.value.left! -= step
                break
            case 'ArrowRight':
                selected_layer.value.left! += step
                break
            case 'Delete':
                deleteLayer() // Call the delete layer function
                break
            case 'o':
                if (event.ctrlKey) {
                    show_import_modal.value = true // Open the import modal
                }
                break
            default:
                return
        }

        if (selected_layer.value) {
            selected_layer.value.setCoords() // Update object boundaries
            canvas.renderAll() // Re-render the canvas

            // Update the position values
            selected_x.value = parseFloat(selected_layer.value.left!.toFixed(2))
            selected_y.value = parseFloat(selected_layer.value.top!.toFixed(2))
        }
    })
}

function flipVertical() {
    if (canvas) {
        const activeObject = canvas.getActiveObject()
        if (activeObject) {
            activeObject.set('flipY', !activeObject.flipY)
            canvas.renderAll()
        }
    }
}

function flipHorizontal() {
    if (canvas) {
        const activeObject = canvas.getActiveObject()
        if (activeObject) {
            activeObject.set('flipX', !activeObject.flipX)
            canvas.renderAll()
        }
    }
}

function snapToCenter(obj: fabric.Object) {
    if (!canvas) return

    const canvasCenterX = canvas.width / 2 + canvas.width
    const canvasCenterY = canvas.height / 2 + canvas.height

    // alert(canvasCenterX)

    // Check horizontal snapping
    if (Math.abs(obj.left! + (obj.width! * obj.scaleX!) / 2 - canvasCenterX) < snap_threshold) {
        obj.left = canvasCenterX - (obj.width! * obj.scaleX!) / 2
    }

    // Check vertical snapping
    if (Math.abs(obj.top! + (obj.height! * obj.scaleY!) / 2 - canvasCenterY) < snap_threshold) {
        obj.top = canvasCenterY - (obj.height! * obj.scaleY!) / 2
    }

    obj.setCoords() // Update object boundaries
}

function toggleBold() {
    if (selected_layer.value && selected_layer.value.type === 'text') {
        const currentFontWeight = selected_layer.value.fontWeight
        selected_layer.value.set('fontWeight', currentFontWeight === 'bold' ? 'normal' : 'bold')
        canvas?.renderAll() // Re-render the canvas
    }
}

function toggleItalic() {
    if (selected_layer.value && selected_layer.value.type === 'text') {
        const currentFontStyle = selected_layer.value.fontStyle
        selected_layer.value.set('fontStyle', currentFontStyle === 'italic' ? 'normal' : 'italic')
        canvas?.renderAll() // Re-render the canvas
    }
}

function initLines() {
    const color = 'red'
    if (!canvas) return

    const line = new fabric.Line([400, 0, 400, 1118], {
        stroke: color,
        selectable: false,
        evented: false,
        strokeDashArray: [5, 5], // dashed line
        excludeFromExport: true, // won't be exported as image/svg
        hoverCursor: 'default',
        layerName: 'Vertical Line',
        top: 1118,
        left: 1200 / 2
    })

    canvas.add(line)
    canvas.renderAll()
}

async function importImage() {
    if (!selected_file.value) {
        return
    }

    const image = await fabric.FabricImage.fromURL(
        `${$page.props.base_url}${selected_file.value.file_url}`,
        {
            crossOrigin: 'anonymous', // Optional: Set cross-origin for CORS
            signal: new AbortController().signal // Optional: Abort signal for cancellation
        },
        {
            left: 1200 / 2, // X position
            top: 1800 / 2, // Y position
            scaleX: 1, // Horizontal scaling
            scaleY: 1, // Vertical scaling
            selectable: true, // Allow selection
            evented: true, // Enable mouse events
            hasControls: true, // Show resize and rotate controls
            lockScalingX: false, // Allow horizontal scaling
            lockScalingY: false, // Allow vertical scaling
            lockRotation: false, // Allow rotation
            originX: 'center', // Horizontal origin
            originY: 'center', // Vertical origin
            layerName: selected_file.value.id // Custom layer name,
        }
    )

    if (canvas) {
        // Resize the image to fit within the canvas dimensions
        const canvasWidth = canvas.width
        const canvasHeight = canvas.height
        const scaleX = canvasWidth / image.width
        const scaleY = canvasHeight / image.height
        const scale = Math.min(scaleX, scaleY)

        image.scale(scale) // Apply the scaling factor
        canvas.add(image)
    }

    refreshLayers()
}

async function saveToOnlineFile() {
    if (!canvas) {
        alert('Canvas is not initialized.')
        return
    }

    const dataURL = canvas.toDataURL({
        format: 'jpeg',
        quality: 1, // Adjust quality (0.1 to 1.0),
        multiplier: 3 // Adjust multiplier for higher resolution
    }) // Convert canvas to JPEG data URL

    const blob = await (await fetch(dataURL)).blob() // Convert data URL to Blob

    const form_data = new FormData()
    form_data.append('file', blob, 'canvas-image.jpg') // Append Blob with a file name
    form_data.append('type', 'upload-image') // Append employee ID
    form_data.append('_method', 'PUT') // Append method for Inertia.js

    router.post(route('dashboard.arta-id.update', { arta_id: show_data.id }), form_data, { preserveState: true })
}

function openToPhotopea() {
    if (!canvas) return

    const employeeLayer = canvas.getObjects('image').find((img: any) => img.layerName === employeeFullName(show_data))

    // 2. Hide the Employee Image
    if (employeeLayer) {
        employeeLayer.visible = false
        canvas.renderAll()
    }

    const dataURL = canvas.toDataURL({
        format: 'jpeg',
        quality: 1,
        multiplier: 2
    })

    if (employeeLayer) {
        employeeLayer.visible = true
        canvas.renderAll()
    }

    const link_data = {
        files: [dataURL],
        resources: ['https://fontiko.com/system/uploads/font-files/93/OpenSans_Condensed-Bold.ttf']
    }

    const encoded = encodeURIComponent(JSON.stringify(link_data))

    console.log(encoded)
    const photopeaUrl = `https://www.photopea.com#${encoded}`

    // Open in new tab
    window.open(photopeaUrl, '_blank')?.focus()
}

function updateQRSize() {
    qr_size.value = qr_code_ref.value?.clientWidth ?? 300
}

onMounted(async () => {
    updateQRSize()

    await document.fonts.ready
    await loadFont('Open Sans', '/fonts/OpenSans-SemiBold.ttf')

    if (canvas_container_ref.value) {
        canvas = new fabric.Canvas('canvasId', {
            width: canvas_container_ref.value.clientWidth,
            height: 1800,
            backgroundColor: 'white'
        })
    }

    zoomToFit()

    fabric.FabricObject.prototype.toObject = (function (toObject) {
        return function (this: fabric.Object) {
            return Object.assign(toObject.call(this), {
                // @ts-ignore
                layerName: this.layerName || '', // Include the custom property
                // @ts-ignore
                text: this.text || null
            })
        }
    })(fabric.Object.prototype.toObject)

    if (!canvas) {
        alert('canvas not initialized')
        return
    }

    await initImageLayers()

    initTextLayers()
    // initLines() // Add a vertical guide line at x = 300

    fabricTriggerFunctions()
    keyboardTriggers()

    setTimeout(() => {
        if (canvas?.getObjects()) {
            refreshLayers()
        }
    }, 500)
})

watch(removing_background, (newValue) => {
    if (newValue) {
        showAdditionalMessage()
    }
})
</script>
