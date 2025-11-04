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
        // Use firstOrCreate to make this seeder safe to run multiple times
        $course = Course::firstOrCreate(
            ['slug' => 'employee-onboarding-template'], // Check if this course exists
            [ // If not, create it
                'title' => 'Employee Onboarding Template',
                'description' => 'A comprehensive, generic onboarding course for new employees. Copy this to your account and edit to fit your company\'s specific needs.',
                'organisation_id' => null, 
                'is_template' => true,
                'is_published' => true,
            ]
        );

        // 2. Add a "Welcome" Lesson
        $lesson1 = $course->lessons()->firstOrCreate(
            ['slug' => 'template-welcome'],
            [
                'title' => 'Welcome to the Team!',
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
            ]
        );

        // 3. Add a "Company Culture" Lesson
        $lesson2 = $course->lessons()->firstOrCreate(
            ['slug' => 'template-culture'],
            [
                'title' => 'Our Company Culture, Mission & Values',
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
            ]
        );

        // 4. Add a "Health & Safety" Lesson
        $lesson3 = $course->lessons()->firstOrCreate(
            ['slug' => 'template-health-safety'],
            [
                'title' => 'Health & Safety in the Workplace',
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
            ]
        );

        // 5. Add an "IT Security" Lesson
        $lesson4 = $course->lessons()->firstOrCreate(
            ['slug' => 'template-it-security'],
            [
                'title' => 'IT Security & Data Privacy',
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
            ]
        );

        // 6. Add a "Communication Tools" Lesson
        $lesson5 = $course->lessons()->firstOrCreate(
            ['slug' => 'template-communication'],
            [
                'title' => 'How We Communicate',
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
            ]
        );

        // 7. Add a "Knowledge Check" Quiz Lesson (now at the end)
        $lesson6 = $course->lessons()->firstOrCreate(
            ['slug' => 'template-quiz'],
            [
                'title' => 'Onboarding Knowledge Check',
                'type' => Lesson::TYPE_QUIZ,
                'content_json' => [], // We use the new tables
                'is_published' => true,
                'position' => 6,
            ]
        );

        // 8. Add Questions to the Quiz (Only if they don't exist)
        if ($lesson6->questions()->count() == 0) {
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
        // ================================================
//  NEW TEMPLATE COURSE: Professional Excellence
// ================================================
$proCourse = Course::firstOrCreate(
    ['slug' => 'professional-excellence-template'],
    [
        'title' => 'Professional Excellence & Workplace Effectiveness',
        'description' => 'A cross-industry professional development course designed to equip employees with timeless soft skills — communication, teamwork, emotional intelligence, and problem-solving — to excel in any organization.',
        'organisation_id' => null,
        'is_template' => true,
        'is_published' => true,
    ]
);

// 1. LESSON: Introduction to Professional Excellence
$lesson1 = $proCourse->lessons()->firstOrCreate(
    ['slug' => 'pro-introduction'],
    [
        'title' => 'Introduction to Professional Excellence',
        'type' => Lesson::TYPE_DEFAULT,
        'content' => '
<h1>Welcome to Professional Excellence</h1>
<p>This course is designed to help you master the habits, skills, and mindset required to thrive in any professional environment. Whether you’re in tech, healthcare, finance, education, or the public sector, excellence is a universal language.</p>

<h2>Why This Course Matters</h2>
<ul>
    <li>Organizations thrive when individuals take ownership of excellence.</li>
    <li>Soft skills are now <strong>power skills</strong> — they determine how far your technical competence will go.</li>
    <li>This course gives you principles that make you indispensable in any team or workplace.</li>
</ul>

<h2>Course Modules</h2>
<ul>
    <li>Effective Communication</li>
    <li>Emotional Intelligence</li>
    <li>Teamwork and Collaboration</li>
    <li>Critical Thinking and Problem-Solving</li>
    <li>Personal Productivity and Time Management</li>
    <li>Continuous Growth Mindset</li>
</ul>

<p>Let’s begin your journey toward workplace mastery and long-term impact.</p>',
        'is_published' => true,
        'position' => 1,
    ]
);

// 2. LESSON: Effective Communication
$lesson2 = $proCourse->lessons()->firstOrCreate(
    ['slug' => 'pro-communication'],
    [
        'title' => 'Effective Communication in the Workplace',
        'type' => Lesson::TYPE_DEFAULT,
        'content' => '
<h1>Effective Communication in the Workplace</h1>
<p>Communication is the foundation of every relationship — personal or professional. In the workplace, it determines productivity, trust, and clarity.</p>

<h2>Key Principles</h2>
<ul>
    <li><strong>Clarity:</strong> Say what you mean, and mean what you say. Avoid jargon and ambiguity.</li>
    <li><strong>Active Listening:</strong> Listen to understand, not to reply. This builds empathy and reduces conflict.</li>
    <li><strong>Feedback:</strong> Give feedback that builds, not breaks. When receiving feedback, focus on improvement, not defense.</li>
    <li><strong>Nonverbal Communication:</strong> Your tone, posture, and facial expressions can speak louder than words.</li>
</ul>

<h2>Communication Mediums</h2>
<ul>
    <li><strong>Email:</strong> Keep it concise and professional. Use subject lines effectively.</li>
    <li><strong>Meetings:</strong> Come prepared, stay focused, and follow up with clear action points.</li>
    <li><strong>Chat Platforms:</strong> Maintain respect and professionalism even in informal exchanges.</li>
</ul>

<p>Mastering communication improves relationships, reduces misunderstandings, and enhances your reputation as a professional.</p>',
        'is_published' => true,
        'position' => 2,
    ]
);

// 3. LESSON: Emotional Intelligence (EQ)
$lesson3 = $proCourse->lessons()->firstOrCreate(
    ['slug' => 'pro-emotional-intelligence'],
    [
        'title' => 'Mastering Emotional Intelligence (EQ)',
        'type' => Lesson::TYPE_DEFAULT,
        'content' => '
<h1>Emotional Intelligence (EQ)</h1>
<p>Emotional Intelligence is the ability to understand and manage your emotions — and those of others. Research shows EQ contributes more to career success than IQ.</p>

<h2>The 5 Components of EQ</h2>
<ol>
    <li><strong>Self-Awareness:</strong> Recognize your emotions and their impact on your behavior.</li>
    <li><strong>Self-Regulation:</strong> Manage impulses, stress, and reactions under pressure.</li>
    <li><strong>Motivation:</strong> Stay driven by purpose, not emotion. Be proactive, not reactive.</li>
    <li><strong>Empathy:</strong> Understand and relate to others’ feelings. This builds trust and influence.</li>
    <li><strong>Social Skills:</strong> Build healthy relationships, resolve conflicts, and inspire collaboration.</li>
</ol>

<h2>Developing EQ</h2>
<ul>
    <li>Pause before reacting emotionally.</li>
    <li>Practice gratitude and perspective-taking.</li>
    <li>Be genuinely curious about others’ viewpoints.</li>
</ul>',
        'is_published' => true,
        'position' => 3,
    ]
);

// 4. LESSON: Teamwork and Collaboration
$lesson4 = $proCourse->lessons()->firstOrCreate(
    ['slug' => 'pro-teamwork'],
    [
        'title' => 'Teamwork and Collaboration',
        'type' => Lesson::TYPE_DEFAULT,
        'content' => '
<h1>Teamwork and Collaboration</h1>
<p>No matter how skilled you are, success in most organizations depends on how well you work with others.</p>

<h2>Why Teamwork Matters</h2>
<ul>
    <li>It drives innovation through diverse perspectives.</li>
    <li>It builds resilience — teams can achieve what individuals cannot.</li>
    <li>It develops shared accountability and ownership of success.</li>
</ul>

<h2>Keys to Effective Collaboration</h2>
<ul>
    <li>Respect every voice in the room.</li>
    <li>Disagree constructively, not destructively.</li>
    <li>Share credit generously; take responsibility willingly.</li>
    <li>Document decisions and ensure clarity of roles.</li>
</ul>

<p>Great teams are not those without conflict — but those that know how to resolve it wisely.</p>',
        'is_published' => true,
        'position' => 4,
    ]
);

// 5. LESSON: Critical Thinking & Problem-Solving
$lesson5 = $proCourse->lessons()->firstOrCreate(
    ['slug' => 'pro-critical-thinking'],
    [
        'title' => 'Critical Thinking & Problem-Solving',
        'type' => Lesson::TYPE_DEFAULT,
        'content' => '
<h1>Critical Thinking & Problem-Solving</h1>
<p>Every organization faces challenges. The ability to think critically and solve problems makes you invaluable.</p>

<h2>Steps in Problem Solving</h2>
<ol>
    <li>Identify the real problem, not just the symptom.</li>
    <li>Gather relevant data and perspectives.</li>
    <li>Generate multiple possible solutions.</li>
    <li>Evaluate options objectively.</li>
    <li>Take action and measure the outcome.</li>
</ol>

<h2>Barriers to Clear Thinking</h2>
<ul>
    <li>Bias and assumptions.</li>
    <li>Emotional reactions clouding judgment.</li>
    <li>Lack of data or relying on hearsay.</li>
</ul>

<p>Be curious, analytical, and courageous enough to question the obvious.</p>',
        'is_published' => true,
        'position' => 5,
    ]
);

// 6. LESSON: Productivity and Time Management
$lesson6 = $proCourse->lessons()->firstOrCreate(
    ['slug' => 'pro-productivity'],
    [
        'title' => 'Personal Productivity & Time Management',
        'type' => Lesson::TYPE_DEFAULT,
        'content' => '
<h1>Personal Productivity & Time Management</h1>
<p>Time is the only truly non-renewable resource. Professionals who manage time effectively manage life effectively.</p>

<h2>Core Principles</h2>
<ul>
    <li><strong>Prioritize:</strong> Focus on what matters most (the 80/20 rule).</li>
    <li><strong>Plan:</strong> Use daily, weekly, and monthly planning habits.</li>
    <li><strong>Eliminate Distractions:</strong> Guard your focus — multitasking is a myth.</li>
    <li><strong>Rest:</strong> Productivity thrives on balance. Schedule recovery as intentionally as work.</li>
</ul>

<p>Tools such as calendars, task managers, and focus timers can multiply your effectiveness when used consistently.</p>',
        'is_published' => true,
        'position' => 6,
    ]
);

// 7. LESSON: Continuous Learning and Growth
$lesson7 = $proCourse->lessons()->firstOrCreate(
    ['slug' => 'pro-growth'],
    [
        'title' => 'Continuous Learning and Growth Mindset',
        'type' => Lesson::TYPE_DEFAULT,
        'content' => '
<h1>Continuous Learning and Growth Mindset</h1>
<p>In the 21st century, the best professionals are lifelong learners. Change is constant; learning keeps you relevant.</p>

<h2>Growth vs Fixed Mindset</h2>
<ul>
    <li><strong>Fixed Mindset:</strong> "I can’t do this" or "I’m just not good at that."</li>
    <li><strong>Growth Mindset:</strong> "I can learn anything with time and effort."</li>
</ul>

<h2>Practices for Continuous Growth</h2>
<ul>
    <li>Seek feedback often.</li>
    <li>Read widely and take short courses regularly.</li>
    <li>Mentor others — teaching deepens your understanding.</li>
</ul>

<p>Excellence is not an act but a consistent process of refinement.</p>',
        'is_published' => true,
        'position' => 7,
    ]
);

// 8. QUIZ LESSON
$lesson8 = $proCourse->lessons()->firstOrCreate(
    ['slug' => 'pro-quiz'],
    [
        'title' => 'Professional Excellence Quiz',
        'type' => Lesson::TYPE_QUIZ,
        'content_json' => [],
        'is_published' => true,
        'position' => 8,
    ]
);

if ($lesson8->questions()->count() == 0) {
    $q1 = $lesson8->questions()->create([
        'question' => 'What is one key element of emotional intelligence?',
        'type' => 'MULTIPLE_CHOICE',
        'position' => 1,
    ]);
    $q1->options()->createMany([
        ['option_text' => 'Self-Awareness', 'is_correct' => true],
        ['option_text' => 'Ignoring Emotions', 'is_correct' => false],
        ['option_text' => 'Reacting Immediately', 'is_correct' => false],
    ]);

    $q2 = $lesson8->questions()->create([
        'question' => 'What is the first step in solving a problem?',
        'type' => 'MULTIPLE_CHOICE',
        'position' => 2,
    ]);
    $q2->options()->createMany([
        ['option_text' => 'Identifying the real problem', 'is_correct' => true],
        ['option_text' => 'Jumping to a conclusion', 'is_correct' => false],
        ['option_text' => 'Asking someone else to solve it', 'is_correct' => false],
    ]);

    $q3 = $lesson8->questions()->create([
        'question' => 'What mindset believes abilities can be developed?',
        'type' => 'MULTIPLE_CHOICE',
        'position' => 3,
    ]);
    $q3->options()->createMany([
        ['option_text' => 'Growth Mindset', 'is_correct' => true],
        ['option_text' => 'Fixed Mindset', 'is_correct' => false],
    ]);

    $q4 = $lesson8->questions()->create([
        'question' => 'Which of these improves team collaboration?',
        'type' => 'MULTIPLE_CHOICE',
        'position' => 4,
    ]);
    $q4->options()->createMany([
        ['option_text' => 'Respecting every voice', 'is_correct' => true],
        ['option_text' => 'Avoiding communication', 'is_correct' => false],
        ['option_text' => 'Taking all credit alone', 'is_correct' => false],
    ]);
}

    }
}

