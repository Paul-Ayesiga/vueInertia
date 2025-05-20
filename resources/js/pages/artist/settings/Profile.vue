<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

import DeleteArtist from '@/components/DeleteArtist.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import ArtistLayout from '@/layouts/ArtistLayout.vue';
import SettingsLayout from '@/layouts/artist/settings/Layout.vue';
import { type BreadcrumbItem, type SharedData, type User } from '@/types';
import { Avatar, AvatarImage, AvatarFallback } from '@/components/ui/avatar';
import { Select, SelectTrigger, SelectValue, SelectContent, SelectGroup, SelectItem } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { Plus, X } from 'lucide-vue-next';
import { ref, reactive } from 'vue';
import { getInitials } from '@/composables/useInitials';
import { Separator } from '@/components/ui/separator';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: '/settings/profile',
    },
];

const page = usePage<SharedData>();
const user = page.props.auth.user as User;
const artist = user.artist;

// Helper function to parse JSON string or return default
const parseJsonOrDefault = (value: string | null | undefined, defaultValue: any[]) => {
    if (!value) return defaultValue;
    try {
        return typeof value === 'string' ? JSON.parse(value) : value;
    } catch (e) {
        return defaultValue;
    }
};

// Initialize form with existing data
const form = useForm({
    name: user.name,
    email: user.email,
    stageName: artist?.stage_name || '',
    bio: artist?.bio || '',
    phoneNumber: artist?.phone_number || '',
    gender: artist?.gender || '',
    nationality: artist?.nationality || '',
    country: artist?.country || '',
    region: artist?.region || '',
    address: artist?.address || '',
    socialMediaLinks: parseJsonOrDefault(artist?.social_media_links, []),
    musicLinks: parseJsonOrDefault(artist?.music_links, []),
    profilePhoto: null as File | null,
    coverPhoto: null as File | null,
});

// Track original values
const originalValues = reactive({
    name: user.name,
    email: user.email,
    stageName: artist?.stage_name || '',
    bio: artist?.bio || '',
    phoneNumber: artist?.phone_number || '',
    gender: artist?.gender || '',
    nationality: artist?.nationality || '',
    country: artist?.country || '',
    region: artist?.region || '',
    address: artist?.address || '',
    socialMediaLinks: parseJsonOrDefault(artist?.social_media_links, []),
    musicLinks: parseJsonOrDefault(artist?.music_links, []),
});

// Helper function to check if a field has changed
const isFieldDirty = (field: string) => {
    if (field === 'socialMediaLinks' || field === 'musicLinks') {
        return JSON.stringify(form[field]) !== JSON.stringify(originalValues[field]);
    }
    return form[field] !== originalValues[field];
};

// Initialize social media links with existing data or default empty link
const socialMediaLinks = ref(
    parseJsonOrDefault(artist?.social_media_links, []).length > 0
        ? parseJsonOrDefault(artist?.social_media_links, [])
        : [{ platform: '', url: '' }]
);

// Initialize music links with existing data or default empty link
const musicLinks = ref(
    parseJsonOrDefault(artist?.music_links, []).length > 0
        ? parseJsonOrDefault(artist?.music_links, [])
        : [{ platform: '', url: '' }]
);

// Log initial data for debugging
// console.log('Artist Data:', {
//     artist,
//     form: form.data(),
//     socialMediaLinks: socialMediaLinks.value,
//     musicLinks: musicLinks.value
// });

// Helper functions for dynamic fields
function addSocialMediaLink() {
    socialMediaLinks.value.push({ platform: '', url: '' });
}

function removeSocialMediaLink(index: number) {
    if (socialMediaLinks.value.length > 1) {
        socialMediaLinks.value.splice(index, 1);
    }
}

function addMusicLink() {
    musicLinks.value.push({ platform: '', url: '' });
}

function removeMusicLink(index: number) {
    if (musicLinks.value.length > 1) {
        musicLinks.value.splice(index, 1);
    }
}

const submit = () => {
    // Create FormData instance for file upload
    const formData = new FormData();

    // Only add fields that have been changed
    const changes = {};

    // Check user fields
    if (form.name !== originalValues.name) {
        formData.append('name', form.name);
        changes.name = form.name;
    }
    if (form.email !== originalValues.email) {
        formData.append('email', form.email);
        changes.email = form.email;
    }
    if (form.stageName !== originalValues.stageName) {
        formData.append('stageName', form.stageName);
        changes.stageName = form.stageName;
    }
    if (form.bio !== originalValues.bio) {
        formData.append('bio', form.bio);
        changes.bio = form.bio;
    }
    if (form.phoneNumber !== originalValues.phoneNumber) {
        formData.append('phoneNumber', form.phoneNumber);
        changes.phoneNumber = form.phoneNumber;
    }
    if (form.gender !== originalValues.gender) {
        formData.append('gender', form.gender);
        changes.gender = form.gender;
    }
    if (form.nationality !== originalValues.nationality) {
        formData.append('nationality', form.nationality);
        changes.nationality = form.nationality;
    }
    if (form.country !== originalValues.country) {
        formData.append('country', form.country);
        changes.country = form.country;
    }
    if (form.region !== originalValues.region) {
        formData.append('region', form.region);
        changes.region = form.region;
    }
    if (form.address !== originalValues.address) {
        formData.append('address', form.address);
        changes.address = form.address;
    }
    if (isFieldDirty('socialMediaLinks')) {
        formData.append('socialMediaLinks', JSON.stringify(socialMediaLinks.value));
        changes.socialMediaLinks = socialMediaLinks.value;
    }
    if (isFieldDirty('musicLinks')) {
        formData.append('musicLinks', JSON.stringify(musicLinks.value));
        changes.musicLinks = musicLinks.value;
    }
    if (form.profilePhoto) {
        formData.append('profilePhoto', form.profilePhoto);
        changes.profilePhoto = 'new file uploaded';
    }
    if (form.coverPhoto) {
        formData.append('coverPhoto', form.coverPhoto);
        changes.coverPhoto = 'new file uploaded';
        console.log('Attaching cover photo to form data:', {
            name: form.coverPhoto.name,
            size: form.coverPhoto.size,
            type: form.coverPhoto.type
        });
    }

    // Log the changes being sent
    console.log('Submitting changes:', changes);
    
    // Debug form data contents
    const formDataEntries = {};
    for (let pair of formData.entries()) {
        formDataEntries[pair[0]] = pair[1] instanceof File 
            ? { name: pair[1].name, size: pair[1].size, type: pair[1].type }
            : pair[1];
    }
    console.log('Form data contents:', formDataEntries);

    form.patch(route('artist.profile.update'), formData, {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            // Update original values after successful submission
            Object.assign(originalValues, {
                name: form.name,
                email: form.email,
                stageName: form.stageName,
                bio: form.bio,
                phoneNumber: form.phoneNumber,
                gender: form.gender,
                nationality: form.nationality,
                country: form.country,
                region: form.region,
                address: form.address,
                socialMediaLinks: JSON.parse(JSON.stringify(socialMediaLinks.value)),
                musicLinks: JSON.parse(JSON.stringify(musicLinks.value)),
            });
            console.log('Update successful, new values:', originalValues);
        },
        onError: (errors) => {
            console.error('Update failed:', errors);
        },
    });
};
</script>

<template>
    <ArtistLayout :breadcrumbs="breadcrumbs">
        <Head title="Profile settings" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Profile information" description="Update your name and email address" />

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-6">
                        <!-- Basic Information -->
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="grid gap-2">
                                <Label for="name">Full Name</Label>
                                <Input id="name" v-model="form.name" required autocomplete="name" placeholder="Full name" />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="stageName">Stage Name</Label>
                                <Input id="stageName" v-model="form.stageName" required placeholder="Stage name" />
                                <InputError :message="form.errors.stageName" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="email">Email</Label>
                                <Input id="email" type="email" v-model="form.email" required autocomplete="email" placeholder="Email address" />
                                <InputError :message="form.errors.email" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="phoneNumber">Phone Number</Label>
                                <Input id="phoneNumber" type="tel" v-model="form.phoneNumber" required placeholder="Phone number" />
                                <InputError :message="form.errors.phoneNumber" />
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="grid gap-2">
                                <Label for="nationality">Nationality</Label>
                                <Input id="nationality" v-model="form.nationality" required placeholder="Nationality" />
                                <InputError :message="form.errors.nationality" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="gender">Gender</Label>
                                <Select v-model="form.gender" required>
                                    <SelectTrigger id="gender">
                                        <SelectValue placeholder="Select gender" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem value="male">Male</SelectItem>
                                            <SelectItem value="female">Female</SelectItem>
                                            <SelectItem value="other">Other</SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.gender" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="country">Country</Label>
                                <Input id="country" v-model="form.country" required placeholder="Country" />
                                <InputError :message="form.errors.country" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="region">Region</Label>
                                <Input id="region" v-model="form.region" required placeholder="Region" />
                                <InputError :message="form.errors.region" />
                            </div>

                            <div class="md:col-span-2 grid gap-2">
                                <Label for="address">Address</Label>
                                <Input id="address" v-model="form.address" required placeholder="Address" />
                                <InputError :message="form.errors.address" />
                            </div>
                        </div>

                        <!-- Bio -->
                        <div class="grid gap-2">
                            <Label for="bio">Bio (Musical Journey)</Label>
                            <Textarea id="bio" v-model="form.bio" required rows="5" placeholder="Tell us about your musical journey..." />
                            <InputError :message="form.errors.bio" />
                        </div>

                        <!-- Profile Photo -->
                        <div class="grid gap-2">
                            <Label for="profilePhoto">Profile Photo</Label>
                            <div class="flex items-start gap-4">
                                <Avatar class="h-20 w-20">
                                    <AvatarImage
                                        v-if="artist?.profile_photo"
                                        :src="`/storage/${artist.profile_photo}`"
                                        :alt="form.name"
                                    />
                                    <AvatarFallback v-else class="text-lg">
                                        {{ getInitials(form.stageName || form.name) }}
                                    </AvatarFallback>
                                </Avatar>
                                <Input
                                    id="profilePhoto"
                                    type="file"
                                    accept="image/*"
                                    @change="(e) => form.profilePhoto = e.target.files[0]"
                                    class="flex-1 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20"
                                />
                            </div>
                            <InputError :message="form.errors.profilePhoto" />
                        </div>

                        <!-- Cover Photo -->
                        <div class="grid gap-2">
                            <Label for="coverPhoto">Cover Photo</Label>
                            <div class="flex flex-col gap-2">
                                <div 
                                    v-if="artist?.cover_photo" 
                                    class="relative h-32 w-full rounded-lg overflow-hidden"
                                >
                                    <img 
                                        :src="`/storage/${artist.cover_photo}`" 
                                        :alt="form.name + ' cover'" 
                                        class="w-full h-full object-cover"
                                    />
                                </div>
                                <Input
                                    id="coverPhoto"
                                    type="file"
                                    accept="image/*"
                                    @change="(e) => form.coverPhoto = e.target.files[0]"
                                    class="flex-1 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20"
                                />
                                <p class="text-sm text-muted-foreground">
                                    Recommended size: 1500 x 500 pixels (3:1 ratio)
                                </p>
                            </div>
                            <InputError :message="form.errors.coverPhoto" />
                        </div>

                        <!-- Social Media Links -->
                        <div class="grid gap-2">
                            <div class="flex items-center justify-between">
                                <Label>Social Media Links</Label>
                                <Button type="button" variant="outline" size="sm" @click="addSocialMediaLink">
                                    <Plus class="w-4 h-4 mr-2" />
                                    Add Link
                                </Button>
                            </div>
                            <div class="space-y-4">
                                <div v-for="(link, index) in socialMediaLinks" :key="index" class="flex flex-col md:flex-row gap-3 md:gap-4">
                                    <div class="flex-[0.4]">
                                        <Select v-model="link.platform">
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
                                    </div>
                                    <div class="flex-1">
                                        <Input type="url" v-model="link.url" placeholder="URL" />
                                    </div>
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
                            <InputError :message="form.errors.socialMediaLinks" />
                        </div>

                        <!-- Music Links -->
                        <div class="grid gap-2">
                            <div class="flex items-center justify-between">
                                <Label>Music Links</Label>
                                <Button type="button" variant="outline" size="sm" @click="addMusicLink">
                                    <Plus class="w-4 h-4 mr-2" />
                                    Add Link
                                </Button>
                            </div>
                            <div class="space-y-4">
                                <div v-for="(link, index) in musicLinks" :key="index" class="flex flex-col md:flex-row gap-3 md:gap-4">
                                    <div class="flex-[0.4]">
                                        <Select v-model="link.platform">
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
                                    </div>
                                    <div class="flex-1">
                                        <Input type="url" v-model="link.url" placeholder="URL" />
                                    </div>
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
                            <InputError :message="form.errors.musicLinks" />
                        </div>
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at">
                        <p class="-mt-4 text-sm text-muted-foreground">
                            Your email address is unverified.
                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                            >
                                Click here to resend the verification email.
                            </Link>
                        </p>

                        <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                            A new verification link has been sent to your email address.
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing">Save Changes</Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Saved.</p>
                        </Transition>
                    </div>
                </form>
            </div>

            <Separator />

            <DeleteArtist />
        </SettingsLayout>
    </ArtistLayout>
</template>
