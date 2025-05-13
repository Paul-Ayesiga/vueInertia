<template>
    <Toaster />
    <AuthBase title="Artist Registration" description="Create your artist account in a few steps" variant="centered">
        <Head title="Artist Registration" />

        <!-- Back Button -->
        <Link
            href="/"
            class="inline-flex items-center text-sm text-muted-foreground hover:text-foreground mb-4 md:mb-6 transition-colors"
        >
            <ArrowLeft class="w-4 h-4 mr-2" />
            Back to Home
        </Link>

        <Form
            v-slot="{ meta, values, validate }"
            as=""
            keep-values
            :validation-schema="toTypedSchema(formSchema[stepIndex - 1])"
            class="w-full max-w-full overflow-hidden"
        >
            <Stepper v-slot="{ isNextDisabled, isPrevDisabled, nextStep, prevStep }" v-model="stepIndex" class="block w-full">
                <form
                    @submit="(e) => {
                        e.preventDefault()
                        validate()

                        if (stepIndex === steps.length && meta.valid) {
                            onSubmit(values)
                        }
                    }"
                    class="w-full"
                >
                    <!-- Stepper Header -->
                    <div class="flex w-full flex-start gap-0.5 sm:gap-1 md:gap-2 px-1 sm:px-4">
                        <StepperItem
                            v-for="step in steps"
                            :key="step.step"
                            v-slot="{ state }"
                            class="relative flex w-full flex-col items-center justify-center"
                            :step="step.step"
                        >
                            <StepperSeparator
                                v-if="step.step !== steps[steps.length - 1].step"
                                class="absolute left-[calc(50%+12px)] right-[calc(-50%+6px)] sm:left-[calc(50%+16px)] sm:right-[calc(-50%+8px)] md:left-[calc(50%+20px)] md:right-[calc(-50%+10px)] top-3 sm:top-4 md:top-5 block h-0.5 shrink-0 rounded-full bg-muted group-data-[state=completed]:bg-primary"
                            />

                            <StepperTrigger as-child>
                                <Button
                                    :variant="state === 'completed' || state === 'active' ? 'default' : 'outline'"
                                    size="icon"
                                    class="z-10 rounded-full shrink-0 h-6 w-6 sm:h-8 sm:w-8 md:h-10 md:w-10"
                                    :class="[state === 'active' && 'ring-2 ring-ring ring-offset-2 ring-offset-background']"
                                    :disabled="state !== 'completed' && !meta.valid"
                                >
                                    <Check v-if="state === 'completed'" class="h-3 w-3 sm:h-4 sm:w-4 md:h-5 md:w-5" />
                                    <template v-if="state === 'active'">
                                        <User v-if="step.step === 1" class="h-3 w-3 sm:h-4 sm:w-4 md:h-5 md:w-5" />
                                        <Music v-if="step.step === 2" class="h-3 w-3 sm:h-4 sm:w-4 md:h-5 md:w-5" />
                                        <KeyRound v-if="step.step === 3" class="h-3 w-3 sm:h-4 sm:w-4 md:h-5 md:w-5" />
                                    </template>
                                    <template v-if="state === 'inactive'">
                                        <User v-if="step.step === 1" class="h-3 w-3 sm:h-4 sm:w-4 md:h-5 md:w-5 text-muted-foreground" />
                                        <Music v-if="step.step === 2" class="h-3 w-3 sm:h-4 sm:w-4 md:h-5 md:w-5 text-muted-foreground" />
                                        <KeyRound v-if="step.step === 3" class="h-3 w-3 sm:h-4 sm:w-4 md:h-5 md:w-5 text-muted-foreground" />
                                    </template>
                                </Button>
                            </StepperTrigger>

                            <div class="mt-2 sm:mt-3 md:mt-5 flex flex-col items-center text-center">
                                <StepperTitle
                                    :class="[state === 'active' && 'text-primary']"
                                    class="text-[10px] sm:text-xs font-semibold transition md:text-sm lg:text-base whitespace-nowrap"
                                >
                                    {{ step.title }}
                                </StepperTitle>
                                <StepperDescription
                                    :class="[state === 'active' && 'text-primary']"
                                    class="hidden text-xs text-muted-foreground transition md:block lg:text-sm"
                                >
                                    {{ step.description }}
                                </StepperDescription>
                            </div>
                        </StepperItem>
                    </div>

                    <!-- Form Steps -->
                    <div class="flex flex-col gap-4 mt-6 md:mt-8 px-1 sm:px-4">
                        <template v-if="stepIndex === 1">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <FormField v-slot="{ componentField }" name="fullName">
                                    <FormItem>
                                        <FormLabel>Full Name</FormLabel>
                                        <FormControl>
                                            <Input type="text" v-bind="componentField" />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <FormField v-slot="{ componentField }" name="email">
                                    <FormItem>
                                        <FormLabel>Email</FormLabel>
                                        <FormControl>
                                            <Input type="email" v-bind="componentField" />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <FormField v-slot="{ componentField }" name="phoneNumber">
                                    <FormItem>
                                        <FormLabel>Phone Number</FormLabel>
                                        <FormControl>
                                            <Input type="tel" v-bind="componentField" />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <FormField v-slot="{ componentField }" name="stageName">
                                    <FormItem>
                                        <FormLabel>Stage Name</FormLabel>
                                        <FormControl>
                                            <Input type="text" v-bind="componentField" />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <FormField v-slot="{ componentField }" name="gender">
                                    <FormItem>
                                        <FormLabel>Gender</FormLabel>
                                        <Select v-bind="componentField">
                                            <FormControl>
                                                <SelectTrigger>
                                                    <SelectValue placeholder="Select gender" />
                                                </SelectTrigger>
                                            </FormControl>
                                            <SelectContent>
                                                <SelectGroup>
                                                    <SelectItem value="male">Male</SelectItem>
                                                    <SelectItem value="female">Female</SelectItem>
                                                    <SelectItem value="other">Other</SelectItem>
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <FormField v-slot="{ componentField }" name="nationality">
                                    <FormItem>
                                        <FormLabel>Nationality</FormLabel>
                                        <FormControl>
                                            <Input type="text" v-bind="componentField" />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <FormField v-slot="{ componentField }" name="country">
                                    <FormItem>
                                        <FormLabel>Country</FormLabel>
                                        <FormControl>
                                            <Input type="text" v-bind="componentField" />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <FormField v-slot="{ componentField }" name="region">
                                    <FormItem>
                                        <FormLabel>Region</FormLabel>
                                        <FormControl>
                                            <Input type="text" v-bind="componentField" />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <FormField v-slot="{ componentField }" name="address" class="md:col-span-2">
                                    <FormItem>
                                        <FormLabel>Address</FormLabel>
                                        <FormControl>
                                            <Input type="text" v-bind="componentField" />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <FormField v-slot="{ componentField }" name="nin">
                                    <FormItem>
                                        <FormLabel>NIN</FormLabel>
                                        <FormControl>
                                            <Input type="text" v-bind="componentField" />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <FormField v-slot="{ field }" name="nationalIdPhotos">
                                    <FormItem>
                                        <FormLabel>National ID Photos</FormLabel>
                                        <FormDescription class="text-xs text-muted-foreground mb-2">
                                            Upload front and back of your National ID (Max. 2 photos)
                                        </FormDescription>
                                        <FormControl>
                                            <Input
                                                type="file"
                                                multiple
                                                accept="image/*"
                                                @change="e => field.onChange(e.target.files)"
                                                class="file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20"
                                            />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>
                            </div>
                        </template>

                        <template v-if="stepIndex === 2">
                            <div class="grid grid-cols-1 gap-4">
                                <FormField v-slot="{ componentField }" name="bio">
                                    <FormItem>
                                        <FormLabel>Bio (Musical Journey)</FormLabel>
                                        <FormControl>
                                            <Textarea v-bind="componentField" rows="5" placeholder="Tell us about your musical journey..." />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <FormField v-slot="{ field }" name="profilePhoto">
                                    <FormItem>
                                        <FormLabel>Profile Photo</FormLabel>
                                        <FormControl>
                                            <Input
                                                type="file"
                                                accept="image/*"
                                                @change="(e: Event) => field.onChange((e.target as HTMLInputElement).files)"
                                                class="file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20"
                                            />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <!-- Social Media Links -->
                                <FormField v-slot="{ field }" name="socialMediaLinks">
                                    <FormItem>
                                        <div class="space-y-4">
                                            <div class="flex items-center justify-between">
                                                <FormLabel>Social Media Links</FormLabel>
                                                <Button type="button" variant="outline" size="sm" @click="addSocialMediaLink">
                                                    <Plus class="w-4 h-4 mr-2" />
                                                    Add Link
                                                </Button>
                                            </div>

                                            <div v-for="(link, index) in socialMediaLinks" :key="index" class="flex flex-col md:flex-row gap-3 md:gap-4">
                                                <FormField :name="`socialMediaLinks.${index}.platform`" v-slot="{ field: platformField }">
                                                    <FormItem class="flex-[0.4]">
                                                        <FormControl>
                                                            <Select v-model="link.platform" @update:modelValue="platformField.onChange">
                                                                <SelectTrigger>
                                                                    <SelectValue :placeholder="link.platform || 'Platform'" />
                                                                </SelectTrigger>
                                                                <SelectContent>
                                                                    <SelectGroup>
                                                                        <SelectItem value="instagram">Instagram</SelectItem>
                                                                        <SelectItem value="twitter">Twitter</SelectItem>
                                                                        <SelectItem value="facebook">Facebook</SelectItem>
                                                                        <SelectItem value="tiktok">TikTok</SelectItem>
                                                                        <SelectItem value="youtube">YouTube</SelectItem>
                                                                    </SelectGroup>
                                                                </SelectContent>
                                                            </Select>
                                                        </FormControl>
                                                        <FormMessage />
                                                    </FormItem>
                                                </FormField>

                                                <FormField :name="`socialMediaLinks.${index}.url`" v-slot="{ field: urlField }">
                                                    <FormItem class="flex-1">
                                                        <FormControl>
                                                            <Input
                                                                type="url"
                                                                v-model="link.url"
                                                                @update:modelValue="urlField.onChange"
                                                                placeholder="URL"
                                                            />
                                                        </FormControl>
                                                        <FormMessage />
                                                    </FormItem>
                                                </FormField>

                                                <Button
                                                    type="button"
                                                    variant="destructive"
                                                    size="icon"
                                                    @click="removeSocialMediaLink(index)"
                                                    :disabled="socialMediaLinks.length === 1"
                                                    class="h-10 w-10 shrink-0"
                                                >
                                                    <X class="w-4 h-4" />
                                                </Button>
                                            </div>
                                        </div>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <!-- Music Links -->
                                <FormField v-slot="{ field }" name="musicLinks">
                                    <FormItem>
                                        <div class="space-y-4">
                                            <div class="flex items-center justify-between">
                                                <FormLabel>Music Links</FormLabel>
                                                <Button type="button" variant="outline" size="sm" @click="addMusicLink">
                                                    <Plus class="w-4 h-4 mr-2" />
                                                    Add Link
                                                </Button>
                                            </div>

                                            <div v-for="(link, index) in musicLinks" :key="index" class="flex flex-col md:flex-row gap-3 md:gap-4">
                                                <FormField :name="`musicLinks.${index}.platform`" v-slot="{ field: platformField }">
                                                    <FormItem class="flex-[0.4]">
                                                        <FormControl>
                                                            <Select v-model="link.platform" @update:modelValue="platformField.onChange">
                                                                <SelectTrigger>
                                                                    <SelectValue :placeholder="link.platform || 'Platform'" />
                                                                </SelectTrigger>
                                                                <SelectContent>
                                                                    <SelectGroup>
                                                                        <SelectItem value="spotify">Spotify</SelectItem>
                                                                        <SelectItem value="apple">Apple Music</SelectItem>
                                                                        <SelectItem value="youtube">YouTube Music</SelectItem>
                                                                        <SelectItem value="soundcloud">SoundCloud</SelectItem>
                                                                        <SelectItem value="other">Other</SelectItem>
                                                                    </SelectGroup>
                                                                </SelectContent>
                                                            </Select>
                                                        </FormControl>
                                                        <FormMessage />
                                                    </FormItem>
                                                </FormField>

                                                <FormField :name="`musicLinks.${index}.url`" v-slot="{ field: urlField }">
                                                    <FormItem class="flex-1">
                                                        <FormControl>
                                                            <Input
                                                                type="url"
                                                                v-model="link.url"
                                                                @update:modelValue="urlField.onChange"
                                                                placeholder="URL"
                                                            />
                                                        </FormControl>
                                                        <FormMessage />
                                                    </FormItem>
                                                </FormField>

                                                <Button
                                                    type="button"
                                                    variant="destructive"
                                                    size="icon"
                                                    @click="removeMusicLink(index)"
                                                    :disabled="musicLinks.length === 1"
                                                    class="h-10 w-10 shrink-0"
                                                >
                                                    <X class="w-4 h-4" />
                                                </Button>
                                            </div>
                                        </div>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>
                            </div>
                        </template>

                        <template v-if="stepIndex === 3">
                            <div class="grid grid-cols-1 gap-4">
                                <FormField v-slot="{ componentField }" name="password">
                                    <FormItem>
                                        <FormLabel>Password</FormLabel>
                                        <FormControl>
                                            <Input type="password" v-bind="componentField" placeholder="Enter your password" />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <FormField v-slot="{ componentField }" name="confirmPassword">
                                    <FormItem>
                                        <FormLabel>Confirm Password</FormLabel>
                                        <FormControl>
                                            <Input type="password" v-bind="componentField" placeholder="Confirm your password" />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <FormField v-slot="{ field }" name="terms">
                                    <FormItem class="flex flex-row items-start space-x-3 space-y-0 rounded-md border p-4">
                                        <FormControl>
                                            <input
                                                type="checkbox"
                                                :checked="field.value"
                                                @change="e => field.onChange(e.target.checked)"
                                                class="h-4 w-4 rounded border-primary text-primary focus:ring-primary"
                                            />
                                        </FormControl>
                                        <div class="space-y-1 leading-none">
                                            <FormLabel>Accept terms and conditions</FormLabel>
                                            <FormDescription>
                                                By accepting our terms and conditions, you agree to our privacy policy and terms of service.
                                            </FormDescription>
                                        </div>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>
                            </div>
                        </template>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex items-center justify-between mt-6 md:mt-8">
                        <Button
                            :disabled="isPrevDisabled"
                            variant="outline"
                            size="sm"
                            class="h-8 px-3 py-1 text-xs sm:h-9 sm:px-4 sm:py-2 sm:text-sm"
                            @click="prevStep"
                        >
                            Back
                        </Button>
                        <div class="flex items-center gap-2 sm:gap-3">
                            <Button
                                v-if="stepIndex !== 3"
                                :type="meta.valid ? 'button' : 'submit'"
                                :disabled="isNextDisabled"
                                size="sm"
                                class="h-8 px-3 py-1 text-xs sm:h-9 sm:px-4 sm:py-2 sm:text-sm"
                                @click="() => meta.valid && nextStep()"
                            >
                                Next
                            </Button>
                            <Button
                                v-if="stepIndex === 3"
                                size="sm"
                                type="submit"
                                class="h-8 px-3 py-1 text-xs sm:h-9 sm:px-4 sm:py-2 sm:text-sm"
                            >
                                Submit
                            </Button>
                        </div>
                    </div>
                </form>
            </Stepper>
        </Form>
    </AuthBase>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form'
import { Input } from '@/components/ui/input'
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'
import { Stepper, StepperDescription, StepperItem, StepperSeparator, StepperTitle, StepperTrigger } from '@/components/ui/stepper'
import { toast } from 'vue-sonner'
import { Toaster } from '@/components/ui/sonner'
import { toTypedSchema } from '@vee-validate/zod'
import { Check, Plus, X, ArrowLeft, User, Music, KeyRound } from 'lucide-vue-next'
import { h, ref } from 'vue'
import * as z from 'zod'
import { Head } from '@inertiajs/vue3'
import AuthBase from '@/layouts/AuthLayout.vue'
import { Link } from '@inertiajs/vue3'
import { Textarea } from '@/components/ui/textarea'
import { FormDescription } from '@/components/ui/form'
import { useField } from 'vee-validate'
import { router } from '@inertiajs/vue3'

const formSchema = [
    z.object({
        fullName: z.string().min(2, 'Full name must be at least 2 characters'),
        email: z.string().email('Invalid email address'),
        phoneNumber: z.string().min(10, 'Phone number must be at least 10 digits'),
        stageName: z.string().min(2, 'Stage name must be at least 2 characters'),
        gender: z.enum(['male', 'female', 'other'], {
            required_error: 'Please select a gender',
        }),
        nationality: z.string().min(2, 'Nationality is required'),
        country: z.string().min(2, 'Country is required'),
        region: z.string().min(2, 'Region is required'),
        address: z.string().min(5, 'Address must be at least 5 characters'),
        nin: z.string().min(14, 'NIN must be 14 characters'),
        nationalIdPhotos: z.any()
            .refine((files) => {
                return files instanceof FileList && files.length > 0;
            }, 'At least one National ID photo is required')
            .refine((files) => {
                if (!(files instanceof FileList)) return false;
                return Array.from(files).every(file => file?.type?.startsWith('image/'));
            }, 'Only image files are allowed')
            .refine((files) => {
                if (!(files instanceof FileList)) return false;
                return files.length <= 2;
            }, 'Maximum of 2 photos allowed'),
    }),
    z.object({
        bio: z.string().min(50, 'Bio must be at least 50 characters'),
        profilePhoto: z.any()
            .refine((files) => {
                return files instanceof FileList && files.length === 1;
            }, 'Profile photo is required')
            .refine((files) => {
                if (!(files instanceof FileList)) return false;
                return Array.from(files).every(file => file?.type?.startsWith('image/'));
            }, 'Only image files are allowed'),
        socialMediaLinks: z.array(
            z.object({
                platform: z.string({ required_error: 'Platform is required' }),
                url: z.string().url('Invalid URL'),
            })
        ).min(1, 'At least one social media link is required'),
        musicLinks: z.array(
            z.object({
                platform: z.string({ required_error: 'Platform is required' }),
                url: z.string().url('Invalid URL'),
            })
        ).min(1, 'At least one music link is required'),
    }),
    z.object({
        password: z.string()
            .min(8, 'Password must be at least 8 characters')
            .regex(/[A-Z]/, 'Password must contain at least one uppercase letter')
            .regex(/[a-z]/, 'Password must contain at least one lowercase letter')
            .regex(/[0-9]/, 'Password must contain at least one number')
            .regex(/[^A-Za-z0-9]/, 'Password must contain at least one special character'),
        confirmPassword: z.string(),
        terms: z.boolean({
            required_error: 'You must accept the terms and conditions',
            invalid_type_error: 'Terms must be accepted',
        }).refine((val) => val === true, {
            message: 'You must accept the terms and conditions',
        }),
    }).refine((data) => data.password === data.confirmPassword, {
        message: "Passwords don't match",
        path: ["confirmPassword"],
    }),
]

const stepIndex = ref(1)
const steps = [
    {
        step: 1,
        title: 'Personal Information',
        description: 'Provide your personal details',
    },
    {
        step: 2,
        title: 'Artist Details',
        description: 'Tell us about your artistry',
    },
    {
        step: 3,
        title: 'Account Setup',
        description: 'Set up your login credentials',
    },
]

// Form state for dynamic fields
const socialMediaLinks = ref([{ platform: '', url: '' }])
const musicLinks = ref([{ platform: '', url: '' }])

// Helper functions for dynamic fields
function addSocialMediaLink() {
    socialMediaLinks.value.push({ platform: '', url: '' })
}

function removeSocialMediaLink(index: number) {
    if (socialMediaLinks.value.length > 1) {
        socialMediaLinks.value.splice(index, 1)
    }
}

function addMusicLink() {
    musicLinks.value.push({ platform: '', url: '' })
}

function removeMusicLink(index: number) {
    if (musicLinks.value.length > 1) {
        musicLinks.value.splice(index, 1)
    }
}

async function onSubmit(values: any) {
    try {
        // Create FormData instance
        const formData = new FormData()

        // Add all fields directly (not nested)
        formData.append('fullName', values.fullName)
        formData.append('email', values.email)
        formData.append('password', values.password)
        formData.append('password_confirmation', values.confirmPassword)
        formData.append('termsAccepted', values.terms ? '1' : '0')
        formData.append('phoneNumber', values.phoneNumber)
        formData.append('stageName', values.stageName)
        formData.append('gender', values.gender)
        formData.append('nationality', values.nationality)
        formData.append('country', values.country)
        formData.append('region', values.region)
        formData.append('address', values.address)
        formData.append('nin', values.nin)
        formData.append('bio', values.bio)

        // Handle file uploads
        if (values.nationalIdPhotos instanceof FileList) {
            Array.from(values.nationalIdPhotos).forEach((file) => {
                formData.append('nationalIdPhotos[]', file)
            })
        }

        if (values.profilePhoto instanceof FileList && values.profilePhoto.length > 0) {
            formData.append('profilePhoto', values.profilePhoto[0])
        }

        // Handle social media links array
        if (Array.isArray(values.socialMediaLinks)) {
            values.socialMediaLinks.forEach((link: any, index: number) => {
                formData.append(`socialMediaLinks[${index}][platform]`, link.platform || '')
                formData.append(`socialMediaLinks[${index}][url]`, link.url || '')
            })
        }

        // Handle music links array
        if (Array.isArray(values.musicLinks)) {
            values.musicLinks.forEach((link: any, index: number) => {
                formData.append(`musicLinks[${index}][platform]`, link.platform || '')
                formData.append(`musicLinks[${index}][url]`, link.url || '')
            })
        }

        // Submit the form
        router.post('/artist/register', formData, {
            forceFormData: true,
            onSuccess: () => {
                toast.success('Registration successful! Please check your email for verification.')
            },
            onError: (errors) => {
                toast.error('Registration failed. Please check the form and try again.')
                console.error(errors)

                // Navigate to the appropriate step based on the error
                if (Object.keys(errors).some(key => ['bio', 'profilePhoto', 'socialMediaLinks', 'musicLinks'].includes(key) ||
                    key.startsWith('socialMediaLinks.') || key.startsWith('musicLinks.'))) {
                    stepIndex.value = 2
                } else if (Object.keys(errors).some(key => ['password', 'password_confirmation', 'termsAccepted'].includes(key))) {
                    stepIndex.value = 3
                } else {
                    stepIndex.value = 1
                }
            }
        })
    } catch (error) {
        console.error('Form submission error:', error)
        toast.error('An error occurred while submitting the form. Please try again.')
    }
}
</script>
