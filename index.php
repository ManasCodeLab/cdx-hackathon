<?php
require_once 'config.php';
require_once 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CodeGenX Hackathon</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=JetBrains+Mono:wght@500&family=Poppins:wght@600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                fontFamily: {
                    sans: ['Inter', 'sans-serif'],
                    mono: ['JetBrains Mono', 'monospace'],
                    heading: ['Poppins', 'sans-serif'],
                },
                extend: {
                    colors: {
                        primary: '#4f46e5',
                        accent: '#0ea5e9',
                        neon: '#a21caf',
                        darkBg: '#18181b',
                        card: '#232334',
                        code: '#0f172a',
                    },
                    boxShadow: {
                        neon: '0 0 8px #a21caf, 0 0 16px #0ea5e9',
                    },
                }
            }
        }
    </script>
    <style>
        .font-heading { font-family: 'Poppins', 'Inter', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=IBM+Plex+Serif:wght@300;400;500;600;700&family=IBM+Plex+Mono:wght@300;400;500;600;700&family=Inter&display=swap" rel="stylesheet">
</head>
<body class="bg-darkBg text-zinc-100 min-h-screen font-sans transition-colors duration-500">

    <!-- NAVBAR -->
    <nav class="flex items-center justify-between px-6 py-4 md:px-16 bg-transparent">
        <div class="flex items-center gap-2">
            <span class="text-accent text-2xl font-mono">{"<"}</span>
            <span class="font-heading text-xl md:text-2xl tracking-wide font-bold">CodeGenX</span>
            <span class="text-neon text-2xl font-mono">{"/>"}</span>
        </div>
        <div class="hidden md:flex items-center gap-8 text-sm font-bold font-mono">
            <a href="#themes" class="hover:text-accent transition-colors">Themes</a>
            <a href="#schedule" class="hover:text-accent transition-colors">Schedule</a>
            <a href="#prizes" class="hover:text-accent transition-colors">Prizes</a>
            <a href="#faq" class="hover:text-accent transition-colors">FAQ</a>
            <button onclick="toggleTheme()" aria-label="Toggle dark mode" class="ml-4 p-2 rounded-lg hover:bg-card/60 transition">
                <svg id="theme-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path id="sun" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m0 14v1m8-8h1M4 12H3m15.364-7.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                </svg>
            </button>
            <a href="#register" class="ml-2 px-4 py-2 bg-accent text-darkBg rounded-full font-bold hover:shadow-neon hover:-translate-y-1 transition">Register</a>
        </div>
        <button id="menuBtn" class="md:hidden p-2" aria-label="Open menu">
            <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </nav>

    <!-- MOBILE MENU -->
    <div id="mobileMenu" class="fixed top-0 left-0 w-full h-full bg-darkBg/95 z-50 flex-col items-center justify-center hidden md:hidden">
        <button id="closeMenu" class="absolute top-6 right-6 text-accent text-4xl">&times;</button>
        <div class="flex flex-col items-center gap-8 mt-32 text-lg font-mono">
            <a href="#themes" onclick="closeMenu()" class="hover:text-accent">Themes</a>
            <a href="#schedule" onclick="closeMenu()" class="hover:text-accent">Schedule</a>
            <a href="#prizes" onclick="closeMenu()" class="hover:text-accent">Prizes</a>
            <a href="#faq" onclick="closeMenu()" class="hover:text-accent">FAQ</a>
            <a href="#register" onclick="closeMenu()" class="px-6 py-2 bg-accent rounded-full text-darkBg font-bold mt-4">Register</a>
        </div>
    </div>

    <!-- HERO SECTION -->
    <section class="relative flex flex-col md:flex-row gap-12 items-center justify-between px-6 md:px-16 pt-12 md:pt-28 pb-20 min-h-[70vh]">
        <!-- HERO CONTENT -->
        <div class="z-10 flex-1 max-w-xl">
            <h1 class="text-4xl md:text-6xl font-heading font-bold mb-4 tracking-tight">
                <span class="text-accent">Code</span>.<span class="text-neon">Build</span>.<span class="text-primary">Innovate</span>.
            </h1>
            <p class="text-lg md:text-2xl text-zinc-300 mb-8 font-mono">
                Join the ultimate hackathon for developers & creators. Collaborate, learn, and win big at <span class="text-accent font-bold">CodeGenX 2025</span>.
            </p>
            <!-- Countdown Timer -->
            <div class="flex items-center gap-4 mb-8">
                <div class="flex flex-col items-center">
                    <span id="days" class="text-3xl md:text-4xl font-mono font-bold text-accent">03</span>
                    <span class="text-xs text-zinc-400 font-mono">Days</span>
                </div>
                <span class="text-xl text-zinc-400">:</span>
                <div class="flex flex-col items-center">
                    <span id="hours" class="text-3xl md:text-4xl font-mono font-bold text-neon">14</span>
                    <span class="text-xs text-zinc-400 font-mono">Hours</span>
                </div>
                <span class="text-xl text-zinc-400">:</span>
                <div class="flex flex-col items-center">
                    <span id="minutes" class="text-3xl md:text-4xl font-mono font-bold text-primary">26</span>
                    <span class="text-xs text-zinc-400 font-mono">Mins</span>
                </div>
                <span class="text-xl text-zinc-400">:</span>
                <div class="flex flex-col items-center">
                    <span id="seconds" class="text-3xl md:text-4xl font-mono font-bold text-accent">09</span>
                    <span class="text-xs text-zinc-400 font-mono">Secs</span>
                </div>
            </div>
            <!-- CTA BUTTONS -->
            <div class="flex gap-4">
                <a href="#register" class="px-8 py-3 bg-accent text-darkBg font-bold rounded-full shadow-neon hover:scale-105 hover:shadow-lg transition-all duration-200">Register Now</a>
                <a href="#faq" class="px-8 py-3 bg-transparent border border-accent text-accent font-bold rounded-full hover:bg-accent hover:text-darkBg transition-all duration-200">Learn More</a>
            </div>
        </div>
        <!-- HERO GRAPHIC / CODE MOTIF -->
        <div class="hidden md:block flex-1 relative">
            <div class="absolute inset-0 w-[420px] h-[420px] bg-gradient-to-tr from-accent/50 via-neon/30 to-primary/30 blur-2xl rounded-full opacity-60 -z-10 animate-pulse"></div>
            <div class="w-[360px] h-[360px] mx-auto bg-card rounded-3xl border-2 border-accent/40 flex items-center justify-center shadow-neon relative overflow-hidden">
                <pre class="text-xs text-zinc-100 font-mono p-6 bg-code rounded-2xl shadow-inner leading-normal">
<span class="text-accent">{"</span>
  <span class="text-primary">event</span>: <span class="text-neon">"CodeGenX Hackathon"</span>,
  <span class="text-primary">date</span>: <span class="text-neon">"July 5-7, 2025"</span>,
  <span class="text-primary">register</span>: <span class="text-neon">"Open Now"</span>
<span class="text-accent">}</span>
                </pre>
                <div class="absolute bottom-4 right-6 text-xs text-zinc-500 font-mono opacity-80">
                    // &lt;code/&gt; your ideas
                </div>
            </div>
        </div>
    </section>

    <!-- THEMES SECTION -->
    <section id="themes" class="max-w-3xl mx-auto mt-[-2rem] md:mt-0 mb-8 px-4 md:px-0">
        <h2 class="font-heading text-2xl md:text-3xl font-bold mb-6 text-accent text-center">Themes</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-4">
            <div class="bg-card rounded-xl p-6 border border-accent/10">
                <div class="font-bold text-neon mb-1">E-Commerce</div>
                <p class="text-zinc-300 text-sm">Build solutions related to online shopping, payments, or digital marketplaces.</p>
            </div>
            <div class="bg-card rounded-xl p-6 border border-accent/10">
                <div class="font-bold text-primary mb-1">Social Impact</div>
                <p class="text-zinc-300 text-sm">Tackle problems with positive impact on society, environment, or communities.</p>
            </div>
            <div class="bg-card rounded-xl p-6 border border-accent/10">
                <div class="font-bold text-accent mb-1">ERP System</div>
                <p class="text-zinc-300 text-sm">Innovate in enterprise resource planning, process automation, or business tools.</p>
            </div>
            <div class="bg-card rounded-xl p-6 border border-accent/10">
                <div class="font-bold text-rose-400 mb-1">Healthcare</div>
                <p class="text-zinc-300 text-sm">Create solutions for patient care, health data, or wellbeing.</p>
            </div>
        </div>
        <div class="text-center mt-2 text-zinc-400 text-sm font-mono">
            <span class="text-accent font-bold">Note:</span> A problem will be formed based on one of these themes and given to participants.
        </div>
    </section>

    <!-- REGISTRATION FORM -->
    <section id="register" class="max-w-2xl mx-auto bg-card/80 rounded-2xl p-8 md:p-12 mt-[-2rem] shadow-lg backdrop-blur border border-accent/10">
        <h2 class="font-heading text-2xl md:text-3xl font-bold mb-2 text-accent">Register for Hackathon</h2>
        <div class="mb-4 text-zinc-200 font-mono text-base">
            Registration fee: <span class="text-accent font-bold">₹<?php echo REGISTRATION_FEE; ?></span>
        </div>
        <form id="registrationForm" class="space-y-6 text-zinc-200">
            <div class="flex gap-4 mb-4">
                <button type="button" id="soloBtn" class="flex-1 px-4 py-2 rounded-lg border border-accent font-mono font-bold transition-all hover:bg-accent/20 bg-accent/20">Solo</button>
                <button type="button" id="teamBtn" class="flex-1 px-4 py-2 rounded-lg border border-accent font-mono font-bold transition-all hover:bg-accent/20">Team</button>
            </div>
            <div class="space-y-4">
                <input type="text" name="name" required placeholder="Your Name" class="w-full px-4 py-3 rounded-lg bg-darkBg/80 border border-accent/20 focus:border-accent outline-none transition font-mono" />
                <input type="email" name="email" required placeholder="Email Address" class="w-full px-4 py-3 rounded-lg bg-darkBg/80 border border-accent/20 focus:border-accent outline-none transition font-mono" />
                <input type="text" name="github_username" required placeholder="GitHub Username" class="w-full px-4 py-3 rounded-lg bg-darkBg/80 border border-accent/20 focus:border-accent outline-none transition font-mono" />
                <div id="teamFields" class="hidden space-y-4">
                    <input type="text" name="team_name" placeholder="Team Name" class="w-full px-4 py-3 rounded-lg bg-darkBg/80 border border-accent/20 focus:border-accent outline-none transition font-mono" />
                    <input type="text" name="team_members" placeholder="Team Members (comma-separated)" class="w-full px-4 py-3 rounded-lg bg-darkBg/80 border border-accent/20 focus:border-accent outline-none transition font-mono" />
                </div>
                <div>
                    <label for="coupon" class="block text-sm mb-1 font-mono">Coupon code (optional):</label>
                    <input id="coupon" name="coupon" type="text" placeholder="Enter coupon (e.g. MANAS80)" class="w-full px-4 py-3 rounded-lg bg-darkBg/80 border border-accent/20 focus:border-accent outline-none transition font-mono" autocomplete="off"/>
                </div>
            </div>
            <div class="border-t border-accent/10 pt-4 text-base font-mono flex items-center justify-between">
                <span>Total:</span>
                <span id="totalAmount" class="text-accent font-bold text-xl">₹<?php echo REGISTRATION_FEE; ?></span>
            </div>
            <div id="appliedMsg" class="text-green-400 font-mono text-sm hidden">Coupon applied: <span class="font-bold">MANAS80</span> (80% off!)</div>
            <div id="invalidMsg" class="text-red-400 font-mono text-sm hidden">Invalid coupon code.</div>
            <button type="submit" class="w-full py-3 rounded-lg bg-accent text-darkBg font-bold font-mono text-lg shadow-neon hover:scale-105 hover:shadow-lg transition-all">Submit Registration</button>
        </form>
    </section>

    <!-- Success Modal -->
    <div id="modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-card p-8 rounded-2xl max-w-md w-full mx-4 relative">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="font-heading text-2xl font-bold text-accent mb-2">Registration Successful!</h3>
                <p class="text-zinc-300 font-mono mb-6">Thank you for registering. We've sent a confirmation email with all the details.</p>
                <button onclick="document.getElementById('modal').style.display='none'" class="px-6 py-2 bg-accent text-darkBg font-mono font-bold rounded-lg hover:bg-accent/90 transition-colors">Close</button>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-card p-8 rounded-2xl max-w-md w-full mx-4 relative">
            <div class="text-center">
                <div class="w-16 h-16 bg-accent/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-accent animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <h3 class="font-heading text-2xl font-bold text-accent mb-2">Processing Registration</h3>
                <p class="text-zinc-300 font-mono">Please wait while we process your registration...</p>
            </div>
        </div>
    </div>

    <!-- SCHEDULE/TRACKS PAGE -->
    <section id="schedule" class="max-w-4xl mx-auto mt-24 px-4 md:px-0">
        <h2 class="font-heading text-2xl md:text-3xl font-bold mb-8 text-accent text-center">Event Schedule</h2>
        <!-- Timeline -->
        <ol class="relative border-l-2 border-accent/40 ml-4">
            <li class="mb-10 ml-8">
                <span class="absolute w-6 h-6 bg-accent/80 rounded-full -left-3 top-0 flex items-center justify-center shadow-neon">
                    <svg class="w-4 h-4 text-darkBg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01"/></svg>
                </span>
                <div class="bg-card border border-accent/10 p-4 rounded-xl shadow-sm">
                    <div class="font-bold text-lg text-accent">July 5, 2025, 11:00 AM</div>
                    <div class="text-zinc-200">Opening Ceremony & Team Formation</div>
                </div>
            </li>
            <li class="mb-10 ml-8">
                <span class="absolute w-6 h-6 bg-neon/80 rounded-full -left-3 top-0 flex items-center justify-center shadow-neon">
                    <svg class="w-4 h-4 text-darkBg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17l4-4-4-4m8 8l-4-4 4-4"/></svg>
                </span>
                <div class="bg-card border border-accent/10 p-4 rounded-xl shadow-sm">
                    <div class="font-bold text-lg text-neon">July 5, 2025, 12:00 PM</div>
                    <div class="text-zinc-200">Theme Announcement & Problem Statement</div>
                </div>
            </li>
            <li class="mb-10 ml-8">
                <span class="absolute w-6 h-6 bg-primary/80 rounded-full -left-3 top-0 flex items-center justify-center shadow-neon">
                    <svg class="w-4 h-4 text-darkBg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </span>
                <div class="bg-card border border-accent/10 p-4 rounded-xl shadow-sm">
                    <div class="font-bold text-lg text-primary">July 7, 2025, 11:00 AM</div>
                    <div class="text-zinc-200">Project Submission Deadline</div>
                </div>
            </li>
            <li class="ml-8">
                <span class="absolute w-6 h-6 bg-accent/80 rounded-full -left-3 top-0 flex items-center justify-center shadow-neon">
                    <svg class="w-4 h-4 text-darkBg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </span>
                <div class="bg-card border border-accent/10 p-4 rounded-xl shadow-sm">
                    <div class="font-bold text-lg text-accent">July 7, 2025, 5:00 PM</div>
                    <div class="text-zinc-200">Judging & Closing Ceremony</div>
                </div>
            </li>
        </ol>
        <!-- Prize Section -->
        <h3 id="prizes" class="font-heading text-2xl font-bold mt-16 mb-8 text-center text-accent">Prizes</h3>
        <div class="flex flex-wrap justify-center gap-8">
            <div class="bg-card p-6 rounded-2xl border border-accent/20 shadow-sm flex flex-col items-center w-full sm:w-64 hover:scale-105 hover:shadow-neon transition-all duration-200">
                <svg class="w-10 h-10 mb-3 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/>
                </svg>
                <div class="font-mono text-xl font-bold mb-1 text-accent">Grand Prize</div>
                <p class="text-zinc-300 text-center">will declare later</p>
            </div>
            <div class="bg-card p-6 rounded-2xl border border-accent/20 shadow-sm flex flex-col items-center w-full sm:w-64 hover:scale-105 hover:shadow-neon transition-all duration-200">
                <svg class="w-10 h-10 mb-3 text-neon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                </svg>
                <div class="font-mono text-xl font-bold mb-1 text-neon">Best Innovation</div>
                <p class="text-zinc-300 text-center">will declare later</p>
            </div>
            <div class="bg-card p-6 rounded-2xl border border-accent/20 shadow-sm flex flex-col items-center w-full sm:w-64 hover:scale-105 hover:shadow-neon transition-all duration-200">
                <svg class="w-10 h-10 mb-3 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <rect x="6" y="6" width="12" height="12" rx="3" stroke-width="2"/>
                </svg>
                <div class="font-mono text-xl font-bold mb-1 text-primary">Best UI/UX</div>
                <p class="text-zinc-300 text-center">will declare later</p>
            </div>
        </div>
    </section>

    <!-- FAQ SECTION -->
    <section id="faq" class="max-w-2xl mx-auto mt-24 px-4 md:px-0 mb-24">
        <h2 class="font-heading text-2xl md:text-3xl font-bold mb-8 text-accent text-center">Frequently Asked Questions</h2>
        <div class="space-y-4">
            <div class="accordion bg-card rounded-xl p-4 border border-accent/10 cursor-pointer transition-all" onclick="toggleFaq(0)">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-zinc-100">Who can participate?</span>
                    <svg id="faq-icon-0" class="w-6 h-6 text-accent transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <div id="faq-panel-0" class="faq-panel mt-2 text-zinc-300 text-sm max-h-0 overflow-hidden transition-all duration-300">
                    Anyone with a passion for coding, design, or innovation!
                </div>
            </div>
            <div class="accordion bg-card rounded-xl p-4 border border-accent/10 cursor-pointer transition-all" onclick="toggleFaq(1)">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-zinc-100">Is it free to join?</span>
                    <svg id="faq-icon-1" class="w-6 h-6 text-accent transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <div id="faq-panel-1" class="faq-panel mt-2 text-zinc-300 text-sm max-h-0 overflow-hidden transition-all duration-300">
                    The registration fee is ₹<?php echo REGISTRATION_FEE; ?>. Apply coupon code <b>MANAS80</b> for 80% off!
                </div>
            </div>
            <div class="accordion bg-card rounded-xl p-4 border border-accent/10 cursor-pointer transition-all" onclick="toggleFaq(2)">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-zinc-100">How are teams formed?</span>
                    <svg id="faq-icon-2" class="w-6 h-6 text-accent transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <div id="faq-panel-2" class="faq-panel mt-2 text-zinc-300 text-sm max-h-0 overflow-hidden transition-all duration-300">
                    You can register solo or with friends; team-matching is available at kickoff.
                </div>
            </div>
            <div class="accordion bg-card rounded-xl p-4 border border-accent/10 cursor-pointer transition-all" onclick="toggleFaq(3)">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-zinc-100">Are there beginner tracks?</span>
                    <svg id="faq-icon-3" class="w-6 h-6 text-accent transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <div id="faq-panel-3" class="faq-panel mt-2 text-zinc-300 text-sm max-h-0 overflow-hidden transition-all duration-300">
                    Absolutely! Workshops and mentors will support all skill levels.
                </div>
            </div>
        </div>
    </section>

    <footer class="text-center py-8 text-zinc-500 text-sm bg-darkBg/70 border-t border-accent/10">
        &copy; 2025 CodeGenX Hackathon. Site developed by 
        <a href="https://linkedin.com/in/aroramanas01/" target="_blank" class="text-accent underline hover:text-neon">Manas Arora</a>.
    </footer>

    <script>
        // Mobile menu
        document.getElementById('menuBtn').onclick = function() {
            document.getElementById('mobileMenu').style.display = 'flex';
        }
        document.getElementById('closeMenu').onclick = function() {
            closeMenu();
        }
        function closeMenu() {
            document.getElementById('mobileMenu').style.display = 'none';
        }

        // Theme toggle
        function toggleTheme() {
            document.documentElement.classList.toggle('dark');
        }

        // Registration form logic
        const soloBtn = document.getElementById('soloBtn');
        const teamBtn = document.getElementById('teamBtn');
        const teamFields = document.getElementById('teamFields');
        let solo = true;
        soloBtn.onclick = function() {
            solo = true;
            soloBtn.classList.add('bg-accent/20');
            teamBtn.classList.remove('bg-accent/20');
            teamFields.classList.add('hidden');
        }
        teamBtn.onclick = function() {
            solo = false;
            teamBtn.classList.add('bg-accent/20');
            soloBtn.classList.remove('bg-accent/20');
            teamFields.classList.remove('hidden');
        }

        // Coupon logic
        const couponInput = document.getElementById('coupon');
        const totalAmount = document.getElementById('totalAmount');
        const appliedMsg = document.getElementById('appliedMsg');
        const invalidMsg = document.getElementById('invalidMsg');
        let discounted = false;
        let couponTimeout;

        function updateAmount() {
            const code = couponInput.value.trim().toUpperCase();
            if (code === "") {
                totalAmount.textContent = "₹<?php echo REGISTRATION_FEE; ?>";
                appliedMsg.classList.add('hidden');
                invalidMsg.classList.add('hidden');
                discounted = false;
                return;
            }

            clearTimeout(couponTimeout);
            couponTimeout = setTimeout(async () => {
                try {
                    const response = await fetch('validate_coupon.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ coupon: code })
                    });
                    
                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Server returned non-JSON response');
                    }
                    
                    const result = await response.json();
                    
                    if (result.success && result.valid) {
                        totalAmount.textContent = "₹" + result.final_amount;
                        appliedMsg.innerHTML = `Coupon applied: <span class="font-bold">${code}</span> (${result.discount_percent}% off!)`;
                        appliedMsg.classList.remove('hidden');
                        invalidMsg.classList.add('hidden');
                        discounted = true;
                    } else {
                        totalAmount.textContent = "₹<?php echo REGISTRATION_FEE; ?>";
                        appliedMsg.classList.add('hidden');
                        invalidMsg.textContent = result.message || 'Invalid coupon code';
                        invalidMsg.classList.remove('hidden');
                        discounted = false;
                    }
                } catch (error) {
                    console.error('Coupon validation failed:', error);
                    totalAmount.textContent = "₹<?php echo REGISTRATION_FEE; ?>";
                    appliedMsg.classList.add('hidden');
                    invalidMsg.textContent = 'Error validating coupon. Please try again.';
                    invalidMsg.classList.remove('hidden');
                    discounted = false;
                }
            }, 500);
        }
        couponInput.addEventListener('input', updateAmount);

        // Form submission
        const form = document.getElementById('registrationForm');
        const loadingOverlay = document.getElementById('loadingOverlay');
        
        form.onsubmit = async function(e) {
            e.preventDefault();
            
            // Show loading overlay
            loadingOverlay.style.display = 'flex';
            
            const formData = new FormData(form);
            formData.append('registration_type', solo ? 'solo' : 'team');
            
            // Log form data for debugging
            console.log('Submitting form data:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
            
            try {
                const response = await fetch('register.php', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });
                
                let result;
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    const text = await response.text();
                    console.error('Server response:', text);
                    throw new Error('Server returned non-JSON response');
                }
                
                console.log('Server response:', result);
                
                if (!response.ok) {
                    throw new Error(result.error || `HTTP error! status: ${response.status}`);
                }
                
                if (result.success) {
                    // Hide loading overlay
                    loadingOverlay.style.display = 'none';
                    
                    // Initiate payment
                    try {
                        const paymentResponse = await fetch('payment.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                email: formData.get('email'),
                                amount: totalAmount.textContent.replace('₹', '')
                            })
                        });
                        
                        const paymentResult = await paymentResponse.json();
                        
                        if (paymentResult.success) {
                            // Show payment modal
                            const modal = document.getElementById('modal');
                            const modalContent = modal.querySelector('.bg-card');
                            
                            // Update modal content for payment
                            modalContent.innerHTML = `
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-accent/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="font-heading text-2xl font-bold text-accent mb-2">Complete Payment</h3>
                                    <p class="text-zinc-300 font-mono mb-4">Amount to pay: <span class="text-accent font-bold">₹${paymentResult.amount}</span></p>
                                    <p class="text-zinc-300 font-mono mb-6">Click the button below to pay via UPI</p>
                                    <a href="${paymentResult.upi_link}" class="inline-block px-6 py-2 bg-accent text-darkBg font-mono font-bold rounded-lg hover:bg-accent/90 transition-colors mb-4">Pay Now</a>
                                    
                                    <div class="mt-8 pt-6 border-t border-accent/10">
                                        <h4 class="font-heading text-lg font-bold text-accent mb-4">Upload Payment Screenshot</h4>
                                        <form id="screenshotForm" class="space-y-4">
                                            <input type="hidden" name="email" value="${formData.get('email')}">
                                            <input type="hidden" name="registration_id" value="${paymentResult.registration_id}">
                                            <div class="relative">
                                                <input type="file" name="screenshot" accept="image/jpeg,image/png" required
                                                    class="w-full px-4 py-3 rounded-lg bg-darkBg/80 border border-accent/20 focus:border-accent outline-none transition font-mono"
                                                    onchange="previewScreenshot(this)">
                                                <div id="screenshotPreview" class="hidden mt-4">
                                                    <div class="max-h-[300px] overflow-y-auto rounded-lg border border-accent/20 bg-darkBg/80 p-2">
                                                        <img src="" alt="Payment Screenshot Preview" class="w-full h-auto object-contain">
                                                    </div>
                                                    <p class="text-zinc-400 text-xs font-mono mt-2">Scroll to view full image</p>
                                                </div>
                                            </div>
                                            <button type="submit" class="w-full px-6 py-2 bg-accent text-darkBg font-mono font-bold rounded-lg hover:bg-accent/90 transition-colors">
                                                Upload Screenshot
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <p class="text-zinc-400 text-sm font-mono mt-4">After payment verification, your registration will be confirmed</p>
                                </div>
                            `;
                            
                            // Add screenshot upload handler
                            const screenshotForm = document.getElementById('screenshotForm');
                            screenshotForm.onsubmit = async function(e) {
                                e.preventDefault();
                                
                                const formData = new FormData(this);
                                const submitButton = this.querySelector('button[type="submit"]');
                                const originalText = submitButton.textContent;
                                
                                try {
                                    submitButton.disabled = true;
                                    submitButton.textContent = 'Uploading...';
                                    
                                    const response = await fetch('upload_screenshot.php', {
                                        method: 'POST',
                                        body: formData
                                    });
                                    
                                    const result = await response.json();
                                    
                                    if (result.success) {
                                        // Show success modal
                                        const modal = document.getElementById('modal');
                                        const modalContent = modal.querySelector('.bg-card');
                                        
                                        // Update modal content for success
                                        modalContent.innerHTML = `
                                            <div class="text-center">
                                                <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                                <h3 class="font-heading text-2xl font-bold text-accent mb-2">Registration Successful!</h3>
                                                <p class="text-zinc-300 font-mono mb-6">Thank you for registering. We've sent a confirmation email with all the details.</p>
                                                <button onclick="document.getElementById('modal').style.display='none'" class="px-6 py-2 bg-accent text-darkBg font-mono font-bold rounded-lg hover:bg-accent/90 transition-colors">Close</button>
                                            </div>
                                        `;
                                        
                                        modal.style.display = 'flex';
                                        
                                        // Reset form
                                        form.reset();
                                        // Reset coupon-related UI
                                        totalAmount.textContent = "₹<?php echo REGISTRATION_FEE; ?>";
                                        appliedMsg.classList.add('hidden');
                                        invalidMsg.classList.add('hidden');
                                        discounted = false;
                                    } else {
                                        throw new Error(result.error || 'Upload failed');
                                    }
                                } catch (error) {
                                    console.error('Screenshot upload failed:', error);
                                    alert(error.message || 'Failed to upload screenshot. Please try again.');
                                } finally {
                                    submitButton.disabled = false;
                                    submitButton.textContent = originalText;
                                }
                            };
                            
                            modal.style.display = 'flex';
                        } else {
                            throw new Error(paymentResult.error || 'Payment initiation failed');
                        }
                    } catch (error) {
                        console.error('Payment initiation failed:', error);
                        alert('Payment initiation failed. Please try again.');
                    }
                    
                    // Reset form
                    form.reset();
                    // Reset coupon-related UI
                    totalAmount.textContent = "₹<?php echo REGISTRATION_FEE; ?>";
                    appliedMsg.classList.add('hidden');
                    invalidMsg.classList.add('hidden');
                    discounted = false;
                } else {
                    throw new Error(result.error || 'Registration failed. Please try again.');
                }
            } catch (error) {
                console.error('Registration failed:', error);
                // Hide loading overlay
                loadingOverlay.style.display = 'none';
                
                // Show error message in a more user-friendly way
                const errorMessage = error.message;
                if (errorMessage.includes('already registered')) {
                    // Show error message near the email field
                    const emailInput = form.querySelector('input[name="email"]');
                    emailInput.classList.add('border-red-500');
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'text-red-400 text-sm mt-1 font-mono';
                    errorDiv.textContent = errorMessage;
                    emailInput.parentNode.appendChild(errorDiv);
                    
                    // Remove error message after 5 seconds
                    setTimeout(() => {
                        emailInput.classList.remove('border-red-500');
                        errorDiv.remove();
                    }, 5000);
                } else {
                    alert(errorMessage);
                }
            }
        };

        // Simple FAQ accordion logic
        function toggleFaq(idx) {
            for (let i = 0; i < 4; i++) {
                const icon = document.getElementById(`faq-icon-${i}`);
                const panel = document.getElementById(`faq-panel-${i}`);
                if (i === idx) {
                    const expanded = panel.style.maxHeight && panel.style.maxHeight !== '0px';
                    if (expanded) {
                        panel.style.maxHeight = '0px';
                        icon.style.transform = '';
                    } else {
                        panel.style.maxHeight = panel.scrollHeight + "px";
                        icon.style.transform = 'rotate(180deg)';
                    }
                } else {
                    panel.style.maxHeight = '0px';
                    icon.style.transform = '';
                }
            }
        }

        // Countdown timer (to July 5, 2025, 11:00am UTC+5:30)
        function updateCountdown() {
            // 5 July 2025 11:00 AM IST = 5 July 2025 05:30 AM UTC
            const eventTime = new Date('2025-07-05T05:30:00Z').getTime();
            const now = Date.now();
            let diff = Math.max(0, eventTime - now);
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            diff -= days * (1000 * 60 * 60 * 24);
            const hours = Math.floor(diff / (1000 * 60 * 60));
            diff -= hours * (1000 * 60 * 60);
            const mins = Math.floor(diff / (1000 * 60));
            diff -= mins * (1000 * 60);
            const secs = Math.floor(diff / 1000);
            document.getElementById('days').textContent = String(days).padStart(2, '0');
            document.getElementById('hours').textContent = String(hours).padStart(2, '0');
            document.getElementById('minutes').textContent = String(mins).padStart(2, '0');
            document.getElementById('seconds').textContent = String(secs).padStart(2, '0');
        }
        setInterval(updateCountdown, 1000);
        updateCountdown();

        // Add this function at the end of your script section
        function previewScreenshot(input) {
            const preview = document.getElementById('screenshotPreview');
            const previewImg = preview.querySelector('img');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('hidden');
            }
        }
    </script>
</body>
</html> 