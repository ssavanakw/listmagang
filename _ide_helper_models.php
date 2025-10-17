<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $division
 * @property string $company
 * @property string|null $background_image
 * @property string $start_date
 * @property string $end_date
 * @property string $city
 * @property string $brand
 * @property string $serial_number
 * @property string|null $logo1
 * @property string|null $logo2
 * @property string|null $signature_image1
 * @property string|null $signature_image2
 * @property string $name_signatory1
 * @property string|null $name_signatory2
 * @property string $role1
 * @property string|null $role2
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate query()
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereBackgroundImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereDivision($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereLogo1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereLogo2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereNameSignatory1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereNameSignatory2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereRole1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereRole2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereSerialNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereSignatureImage1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereSignatureImage2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereUpdatedAt($value)
 */
	class Certificate extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $date
 * @property string $activities
 * @property string $challenges
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|DailyReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyReport query()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyReport whereActivities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyReport whereChallenges($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyReport whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyReport whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyReport whereUserId($value)
 */
	class DailyReport extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $fullname
 * @property \Illuminate\Support\Carbon $born_date
 * @property string $student_id
 * @property string $email
 * @property string $gender
 * @property string $phone_number
 * @property string $institution_name
 * @property string $study_program
 * @property string $faculty
 * @property string $current_city
 * @property string $internship_reason
 * @property string $internship_type
 * @property string $internship_arrangement
 * @property string $current_status
 * @property string $english_book_ability
 * @property string|null $supervisor_contact
 * @property string $internship_interest
 * @property string|null $internship_interest_other
 * @property string|null $design_software
 * @property string|null $video_software
 * @property string|null $programming_languages
 * @property string|null $digital_marketing_type
 * @property string|null $digital_marketing_type_other
 * @property string|null $laptop_equipment
 * @property string|null $owned_tools
 * @property string|null $owned_tools_other
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property string|null $internship_info_sources
 * @property string|null $internship_info_other
 * @property string|null $cv_ktp_portofolio_pdf
 * @property string|null $portofolio_visual
 * @property string|null $current_activities
 * @property string|null $boarding_info
 * @property string $family_status
 * @property string|null $parent_wa_contact
 * @property string|null $social_media_instagram
 * @property string $internship_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $user_id
 * @property-read array $info_sources_list
 * @property-read array $owned_tools_list
 * @property-read string $status_badge_class
 * @property-read string $status_label
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration accepted()
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration active()
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration completed()
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration exited()
 * @method static \Database\Factories\InternshipRegistrationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration isNew()
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration pending()
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration query()
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration rejected()
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration status(string $status)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereBoardingInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereBornDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereCurrentActivities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereCurrentCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereCurrentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereCvKtpPortofolioPdf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereDesignSoftware($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereDigitalMarketingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereDigitalMarketingTypeOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereEnglishBookAbility($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereFaculty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereFamilyStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereInstitutionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereInternshipArrangement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereInternshipInfoOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereInternshipInfoSources($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereInternshipInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereInternshipInterestOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereInternshipReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereInternshipStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereInternshipType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereLaptopEquipment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereOwnedTools($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereOwnedToolsOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereParentWaContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration wherePortofolioVisual($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereProgrammingLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereSocialMediaInstagram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereStudyProgram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereSupervisorContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternshipRegistration whereVideoSoftware($value)
 */
	class InternshipRegistration extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $leave_type
 * @property string $leave_date
 * @property string $reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveRequest whereLeaveDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveRequest whereLeaveType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveRequest whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveRequest whereUserId($value)
 */
	class LeaveRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|PendingTask newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PendingTask newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PendingTask query()
 * @method static \Illuminate\Database\Eloquent\Builder|PendingTask whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PendingTask whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PendingTask whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PendingTask whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PendingTask whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PendingTask whereUserId($value)
 */
	class PendingTask extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone_number
 * @property string|null $profile_picture
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $role
 * @property int $is_online
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DailyReport> $dailyReports
 * @property-read int|null $daily_reports_count
 * @property-read \App\Models\InternshipRegistration|null $internshipRegistration
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LeaveRequest> $leaveRequests
 * @property-read int|null $leave_requests_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PendingTask> $pendingTasks
 * @property-read int|null $pending_tasks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

