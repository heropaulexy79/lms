<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use Illuminate\Support\Str;

class CourseTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create the Master Template Course
        $course = Course::create([
            'title' => 'Employee Onboarding Template',
            'slug' => 'employee-onboarding-template',
            'description' => 'A comprehensive, generic onboarding course for new employees. Copy this to your account and edit to fit your company\'s specific needs.',
            'organisation_id' => null, // Belongs to no one
            'is_template' => true,
            'is_published' => true, // Published so it appears in the gallery
        ]);

        // 2. Add a "Welcome" Lesson
        $lesson1 = $course->lessons()->create([
            'title' => 'Welcome to the Team!',
            'slug' => 'template-welcome',
            'type' => Lesson::TYPE_DEFAULT,
            'content' => '<h1>Welcome to the Team!</h1>
<p>We are thrilled to have you join us at [Company Name]. This onboarding course is your first step to becoming an integral part of our team.</p>
<h2>What to Expect</h2>
<p>This course is designed to guide you through our company\'s mission, our culture, and the essential policies you need to know to get started. It\'s self-paced, so feel free to take your time and absorb the information.</p>
<p>Your journey will cover:</p>
<ul>
    <li>Our Mission and Values</li>
    <li>Key Health & Safety Guidelines</li>
    <li>IT Security & Best Practices</li>
    <li>Our tools and how we communicate</li>
</ul>
<h2>Your First Week</h2>
<p>Beyond this course, your manager and your assigned "buddy" will be in touch to help you get settled, set up your accounts, and walk you through your specific role and first projects.</p>
<p>If you have any questions, don\'t hesitate to reach out to your manager or the HR department at [Insert HR Email or Contact Person].</p>
<p>Let\'s get started!</p>',
            'is_published' => true,
            'position' => 1,
        ]);

        // 3. Add a "Company Culture" Lesson
        $lesson2 = $course->lessons()->create([
            'title' => 'Our Company Culture, Mission & Values',
            'slug' => 'template-culture',
            'type' => Lesson::TYPE_DEFAULT,
            'content' => '<h1>Our Company Culture, Mission & Values</h1>
<h2>Our Mission</h2>
<p>Our mission is to [Insert Company Mission Statement, e.g., "deliver outstanding results for our clients through innovation and integrity"].</p>

<h2>Our Vision</h2>
<p>We are working to build a future where [Insert Company Vision Statement, e.g., "every business has access to sustainable technology"].</p>

<h2>Our Core Values</h2>
<p>Our values are the foundation of our culture. They guide how we work together and how we treat our customers.</p>

<h3>1. [Insert Value 1, e.g., Integrity]</h3>
<p><strong>What this means:</strong> We are honest and transparent in all our dealings. We build trust by doing the right thing, even when no one is watching. [Insert 1-2 more sentences of specific examples].</p>

<h3>2. [Insert Value 2, e.g., Collaboration]</h3>
<p><strong>What this means:</strong> We are one team. We support each other, share knowledge openly, and celebrate our collective successes. We believe we are stronger together. [Insert 1-2 more sentences of specific examples].</p>

<h3>3. [Insert Value 3, e.g., Innovation]</h3>
<p><strong>What this means:</strong> We are curious and always learning. We are not afraid to challenge the status quo and experiment with new ideas to find better solutions for our customers. [Insert 1-2 more sentences of specific examples].</p>
',
            'is_published' => true,
            'position' => 2,
        ]);

        // 4. Add a "Health & Safety" Lesson
        $lesson3 = $course->lessons()->create([
            'title' => 'Health & Safety in the Workplace',
            'slug' => 'template-health-safety',
            'type' => Lesson::TYPE_DEFAULT,
            'content' => '<h1>Health & Safety in the Workplace</h1>
<p>Your physical and mental well-being are our top priority. This lesson covers your responsibilities and the resources available to you.</p>

<h2>Emergency Procedures</h2>
<p>Please take a moment to familiarize yourself with your new environment:</p>
<ul>
    <li><strong>Fire Exits:</strong> Locate the two nearest fire exits to your workstation. In the event of a fire alarm, do not use the elevators.</li>
    <li><strong>Assembly Point:</strong> Our building\'s emergency assembly point is located at [Insert Location, e.g., "the park across the street"].</li>
    <li><strong>Emergency Contacts:</strong> The contact for our building\'s security desk is [Insert Number]. For all medical, fire, or police emergencies, dial [Insert Emergency Number].</li>
</ul>

<h2>Workplace Health</h2>
<ul>
    <li><strong>First Aid:</strong> First aid kits are located [Insert location, e.g., "in the kitchen on each floor"]. Our certified first aid officers are [Insert Names or HR Contact].</li>
    <li><strong>Incident Reporting:</strong> Report *all* workplace incidents, injuries, or hazards—no matter how small—immediately to [Insert contact person or department, e.g., "your manager or the HR portal"]. This helps us prevent future accidents.</li>
    <li><strong>Tidy Workspace:</strong> Keep your personal workspace and common areas clean and free of clutter to prevent trips and hazards.</li>
</ul>

<h2>Ergonomics & Well-being</h2>
<p>Working comfortably is key to long-term health.</p>
<ul>
    <li><strong>Your Workstation:</strong> Adjust your chair, desk, and monitor to the correct ergonomic positions. Your monitor should be at eye level, and your arms at a 90-degree angle when typing.</li>
    <li><strong>Take Breaks:</strong> Remember to take short, regular breaks to stand up, stretch, and rest your eyes. We recommend the 20-20-20 rule: every 20 minutes, look at something 20 feet away for 20 seconds.</li>
    <li><strong>Mental Health Resources:</strong> We offer [Insert resource, e.g., "an Employee Assistance Program (EAP)"] that provides confidential support. Please see the HR portal for more details.</li>
</ul>',
            'is_published' => true,
            'position' => 3,
        ]);

        // 5. Add an "IT Security" Lesson
        $lesson4 = $course->lessons()->create([
            'title' => 'IT Security & Data Privacy',
            'slug' => 'template-it-security',
            'type' => Lesson::TYPE_DEFAULT,
            'content' => '<h1>IT Security & Data Privacy</h1>
<p>As an employee, you are a guardian of our company\'s and our clients\' data. Protecting this information is a critical part of your role.</p>

<h2>Your Account</h2>
<ul>
    <li><strong>Password Policy:</strong> Your password must be at least [e.g., 12] characters long and include a mix of uppercase, lowercase, numbers, and symbols. Use a unique password for your company account.</li>
    <li><strong>Multi-Factor Authentication (MFA):</strong> You will be required to set up MFA. This adds a crucial layer of security to your account.</li>
    <li><strong>Never Share:</strong> Never share your login credentials with *anyone*, including IT staff or your manager.</li>
</ul>

<h2>Phishing & Social Engineering</h2>
<p>This is the number one threat you will face. Phishing is an attempt to trick you into revealing sensitive information (like your password) or installing malware.</p>
<p><strong>Red Flags to Watch For:</strong></p>
<ul>
    <li><strong>Urgency:</strong> "Your account will be suspended in 24 hours!"</li>
    <li><strong>Strange Sender:</strong> An email from a "colleague" but with a public email address (e.g., @gmail.com).</li>
    <li><strong>Bad Links:</strong> Hover over a link before you click. Does the URL look suspicious?</li>
    <li><strong>Unexpected Attachments:</strong> Never open an attachment you weren\'t expecting.</li>
</ul>
<p><strong>WHAT TO DO:</strong> If you receive a suspicious email, do not click, do not reply, and do not open attachments. Report it immediately to the IT department by [Insert Procedure, e.g., "using the Report Phishing button in Outlook" or "forwarding it to security@company.com"].</p>

<h2>Acceptable Use Policy</h2>
<ul>
    <li><strong>Company Devices:</strong> Company-issued laptops and phones are for business use. Incidental personal use is acceptable, but do not install unauthorized software.</li>
    <li><strong>Public Wi-Fi:</strong> Avoid using public, unsecured Wi-Fi (like at a coffee shop) for sensitive work. If you must, always connect through the company VPN.</li>
    <li><strong>Data Handling:</strong> Do not download sensitive company data to personal devices or unauthorized cloud services (like a personal Google Drive).</li>
</ul>',
            'is_published' => true,
            'position' => 4,
        ]);

        // 6. Add a "Communication Tools" Lesson
        $lesson5 = $course->lessons()->create([
            'title' => 'How We Communicate',
            'slug' => 'template-communication',
            'type' => Lesson::TYPE_DEFAULT,
            'content' => '<h1>How We Communicate</h1>
<p>Clear and efficient communication is vital to our teamwork. Here are the primary tools we use and the "rules of the road" for each.</p>

<h2>Our Main Tools</h2>
<ul>
    <li><strong>[Insert Chat Tool, e.g., Slack/Teams]:</strong> This is for all internal, real-time communication. It\'s for quick questions, team collaboration, and informal updates.</li>
    <li><strong>Email:</strong> This is for more formal, official communication. This includes communicating with external clients, company-wide announcements, and complex reports.</li>
    <li><strong>[Insert Project Mgt Tool, e.g., Asana/Trello]:</strong> This is our "single source of truth" for project work. All tasks, deadlines, and project-related files should live here.</li>
    <li><strong>[Insert Video Call Tool, e.g., Zoom/Google Meet]:</strong> For all internal and external meetings.</li>
</ul>

<h2>Best Practices</h2>
<ul>
    <li><strong>Chat Etiquette:</strong> Respect your colleagues\' focus time. Use public channels for team questions (so everyone can learn) and direct messages for private, 1-on-1 topics. Set your status (e.g., "In a meeting," "Focusing") to manage expectations.</li>
    <li><strong>Email Etiquette:</strong> Use a clear and concise subject line. Be professional and proofread your message before sending, especially to clients.</li>
    <li><strong>Meeting Etiquette:</strong> Be on time. Mute your microphone when you are not speaking. Have your camera on (if it\'s our company policy) to stay engaged. Stick to the agenda so we can end on time.</li>
</ul>
<p>Your manager will add you to the correct channels and projects to get you started.</p>',
            'is_published' => true,
            'position' => 5,
        ]);

        // 7. Add a "Knowledge Check" Quiz Lesson (now at the end)
        $lesson6 = $course->lessons()->create([
            'title' => 'Onboarding Knowledge Check',
            'slug' => 'template-quiz',
            'type' => Lesson::TYPE_QUIZ,
            'content_json' => [], // We use the new tables
            'is_published' => true,
            'position' => 6,
        ]);

        // 8. Add Questions to the Quiz
        $q1 = $lesson6->questions()->create([
            'question' => 'What is one of the company\'s core values mentioned in the "Our Culture" lesson?',
            'type' => 'MULTIPLE_CHOICE',
            'position' => 1,
        ]);

        $q1->options()->createMany([
            ['option_text' => '[Edit Me] Value 1', 'is_correct' => true],
            ['option_text' => '[Edit Me] A different value', 'is_correct' => false],
            ['option_text' => '[Edit Me] Another value', 'is_correct' => false],
        ]);

        $q2 = $lesson6->questions()->create([
            'question' => 'If you receive a suspicious email, you should click the link to see if it\'s real.',
            'type' => 'TRUE_FALSE',
            'position' => 2,
        ]);

        $q2->options()->createMany([
            ['option_text' => 'True', 'is_correct' => false],
            ['option_text' => 'False', 'is_correct' => true],
        ]);

        $q3 = $lesson6->questions()->create([
            'question' => 'What should you do if you get a minor injury at work?',
            'type' => 'MULTIPLE_CHOICE',
            'position' => 3,
        ]);

        $q3->options()->createMany([
            ['option_text' => 'Ignore it and hope it goes away.', 'is_correct' => false],
            ['option_text' => 'Report it immediately to [Insert Contact Person].', 'is_correct' => true],
            ['option_text' => 'Only report it if it gets worse.', 'is_correct' => false],
            ['option_text' => 'Post about it on [Insert Chat Tool].', 'is_correct' => false],
        ]);
        
        $q4 = $lesson6->questions()->create([
            'question' => 'Which tool is used for all internal, day-to-day team communication?',
            'type' => 'MULTIPLE_CHOICE',
            'position' => 4,
        ]);

        $q4->options()->createMany([
            ['option_text' => 'Email', 'is_correct' => false],
            ['option_text' => '[Insert Chat Tool, e.g., Slack/Teams]', 'is_correct' => true],
            ['option_text' => 'Carrier Pigeon', 'is_correct' => false],
        ]);

        $q5 = $lesson6->questions()->create([
            'question' => 'You are at a coffee shop and need to check your work email. What is the most secure way to do this?',
            'type' => 'MULTIPLE_CHOICE',
            'position' => 5,
        ]);

        $q5->options()->createMany([
            ['option_text' => 'It\'s safe, coffee shop Wi-Fi is fine.', 'is_correct' => false],
            ['option_text' => 'Connect to the public Wi-Fi, but only for a few minutes.', 'is_correct' => false],
            ['option_text' => 'Connect to the company VPN before accessing any work materials.', 'is_correct' => true],
            ['option_text' => 'Ask a stranger to watch your laptop while you get your coffee.', 'is_correct' => false],
        ]);
    }
}

