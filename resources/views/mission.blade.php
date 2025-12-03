
<x-app-layout>

    <style>
        :root{
            --brand-green: #18411a;
            --brand-green-light: #7bb25a;
            --muted-pink-copy: #7B5A61;
            --card-border-pink: rgba(247,183,200,0.18);
            --card-shadow: 0 10px 30px rgba(33,37,41,0.06);
            --orange-sdg: #F2BA90;
            --green-sdg: #5CA748;


        }

        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .mission-wrap {
            padding-top: 48px;
            padding-bottom: 48px;
        }

        .mission-info {
            background: #fff;
            border-radius: 16px;
            padding: 28px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(15,23,42,0.03);
        }

        .mission-features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 36px;
        }

        .feature-card {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid var(--card-border-pink);
            box-shadow: 0 8px 20px rgba(9,30,66,0.04);
            min-height: 170px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            align-items: center;
            text-align: center;
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(180deg, rgba(107,191,89,0.15), rgba(107,191,89,0.05));
            color: var(--brand-green);
        }

        .feature-title {
            color: var(--brand-green);
            font-weight: 700;
            margin-top: 8px;
        }

        .feature-desc {
            color: var(--muted-pink-copy);
            font-size: 14px;
        }

        .sdg-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .sdg-card {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            display: flex;
            gap: 14px;
            align-items: flex-start;
            border: 1px solid var(--card-border-pink);
            box-shadow: 0 8px 20px rgba(9,30,66,0.04);
        }

        .sdg-badge2 {
            width: 16px;
            height: 56px;
            border-radius: 10px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 20px;
        }

                .sdg-badge {
            width: 200px;
            height: 56px;
            border-radius: 10px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 20px;
        }

        .panel {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 8px 24px rgba(9,30,66,0.04);
            border: 1px solid rgba(15,23,42,0.03);
            margin-bottom: 24px;
        }

        .panel h4 {
            color: var(--brand-green);
            font-weight: 800;
            margin-bottom: 10px;
        }

        .panel p {
            color: #334155;
            line-height: 1.7;
            margin-bottom: 10px;
        }

        .muted-copy {
            color: var(--muted-pink-copy);
        }

        @media (max-width: 992px) {
            .mission-features {
                grid-template-columns: repeat(2, 1fr);
            }
            .sdg-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .mission-features {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <main class="bg-seiun-sky min-h-screen">
        <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 mission-wrap">
            <div class="max-w-4xl mx-auto text-center">

                <h1 class="text-4xl md:text-5xl font-extrabold text-dark-green mb-4">
                    Our Mission
                </h1>

                <p class="text-xl text-magenta-secondary font-medium mb-10">
                    To serve as Makati's essential web directory for public green spaces, 
                    empowering communities to discover, connect, 
                    and collaborate on projects that promote environmental 
                    health and local well-being.
                </p>

                {{-- WHAT WE DO --}}
                <div class="mission-info text-left">
                    <h2 class="text-2xl font-bold text-dark-green mb-4">What we do</h2>

                    <p class="text-magenta-secondary mb-4">
                        EcoSpaces builds a local ecosystem of eco-friendly places and events —
                        helping residents discover nearby parks, botanical gardens,
                        makerspaces, and sustainability-focused meetups. 
                    </p>

                    <h3 class="text-lg font-semibold text-dark-green mb-2">How we help</h3>

                    <ul class="list-disc list-inside text-magenta-secondary space-y-2 mb-6">
                        <li>Curate and list sustainable spaces and community-driven events.</li>
                        <li>Allow the community to leave reviews and ratings for transparency.</li>
                        <li>Provide tools for organizers to host accessible green initiatives.</li>
                    </ul>

                    <div class="flex justify-center">
                        <a href="{{ route('dashboard') }}"
                           class="bg-magenta-secondary text-white px-6 py-3 rounded-lg font-semibold hover:opacity-95 transition">
                            Explore Spaces
                        </a>
                    </div>
                </div>

                {{-- FEATURE CARDS --}}
                <div class="mission-features mt-12">

                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor">
                                <path d="M12 11.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                                <path d="M12 2C8.7 2 6 4.7 6 8c0 5.3 6 12 6 12s6-6.7 6-12c0-3.3-2.7-6-6-6z"/>
                            </svg>
                        </div>
                        <div class="feature-title">Discover</div>
                        <div class="feature-desc">Find hidden green gems and sustainable spaces throughout Makati.</div>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5"/>
                                <circle cx="17" cy="7" r="4"/>
                                <path d="M9 7a4 4 0 1 1 0 8"/>
                            </svg>
                        </div>
                        <div class="feature-title">Connect</div>
                        <div class="feature-desc">Join community events, clean-ups, and workshops that make a difference.</div>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor">
                                <path d="M12 2v20"/>
                                <path d="M5 11h14"/>
                                <path d="M7 7h10"/>
                            </svg>
                        </div>
                        <div class="feature-title">Contribute</div>
                        <div class="feature-desc">Share your favorite eco-spaces and help build a sustainable community.</div>
                    </div>

                </div>

                {{-- SDG SUMMARY --}}
                <div class="mt-14 text-center">
                    <h3 class="text-2xl font-extrabold text-dark-green mb-2">
                        Supporting the UN Sustainable Development Goals
                    </h3>

                    <p class="muted-copy max-w-3xl mx-auto leading-relaxed">
                        EcoSpaces is committed to advancing the United Nations 
                        Sustainable Development Goals through technology and 
                        community engagement. We focus on two key areas where 
                        we can make the biggest impact.
                    </p>

                    <div class="sdg-grid mt-6">

                        <div class="sdg-card">
                            <div class="sdg-badge bg-orange-500 text-white">11</div>
                            <div>
                                <div class="font-semibold text-green-900 text-lg">
                                    Sustainable Cities & Communities
                                </div>
                                <div class="text-sm text-gray-700 mb-1">
                                    Make cities and human settlements inclusive, safe, resilient and sustainable
                                </div>
                                <div class="text-sm text-gray-600 leading-relaxed">
                                    EcoSpaces supports equitable access to safe, inclusive,
                                    and accessible parks and public spaces. Barangay-based
                                    filters encourage nearby exploration and reduce
                                    transportation emissions.
                                </div>
                            </div>
                        </div>

                        <div class="sdg-card">
                            <div class="sdg-badge bg-light-green text-white">3</div>
                            <div>
                                <div class="font-semibold text-green-900 text-lg">
                                    Good Health & Well-being
                                </div>
                                <div class="text-sm text-gray-700 mb-1">
                                    Ensure healthy lives and promote well-being for all at all ages
                                </div>
                                <div class="text-sm text-gray-600 leading-relaxed">
                                    Access to nature helps reduce stress, anxiety, and
                                    emotional fatigue. Community-centered activities foster
                                    social connection and improved well-being.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

             {{-- EXTENDED SDG ALIGNMENT --}}
<div class="max-w-5xl mx-auto mt-14 px-4">
    <section class="panel mb-6">

       <h4 class="mb-3">Creating Lasting Impact</h4>

<p class="mt-3 mx-auto max-w-3xl text-center text-gray-700 leading-relaxed">
    By connecting people with nature and sustainable practices, 
    EcoSpaces helps build resilient communities that prioritize 
    both environmental health and human well-being. Every green 
    space discovered, every community event attended, and every 
    sustainable practice adopted contributes to a more sustainable 
    future for Makati and beyond.
</p>


    </section>
</div>

            </div>
        </section>

        <hr class="w-80 border-magenta-secondary my-6 mx-auto">

{{-- MEET THE DEVELOPMENT TEAM SECTION --}}
<section class="container mx-auto px-4 sm:px-6 lg:px-8 py-20 bg-seiun-sky">
    <div class="max-w-4xl mx-auto text-center mb-12">
        
        <h2 class="text-4xl font-extrabold text-dark-green mb-4">
            Meet the Development Team
        </h2>
        
        <p class="text-lg text-magenta-secondary max-w-2xl mx-auto leading-relaxed">
            We're a passionate team of developers, designers, and environmental advocates
            dedicated to building technology that makes a positive impact on our communities
            and planet.
        </p>
        
    </div>

    {{-- TEAM CARDS GRID (4 Columns) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        {{-- TEAM MEMBER CARD 1 --}}
        <div class="bg-white p-6 rounded-xl border border-pink-logo/30 shadow-md flex flex-col items-center text-center">
            
            {{-- Avatar Placeholder (Using light-green background from mockup) --}}
            <img src="{{ asset('images/arkin.jpg') }}" alt="Arkin Reinier Aguilar" class="w-24 h-24 rounded-full object-cover border-4 border-light-green mb-4">

            <h3 class="font-bold text-dark-green text-lg">Arkin Reinier Aguilar</h3>
            <p class="text-sm text-light-green font-semibold mb-3">Front-end Developer & UX/UI Designer</p>

            <p class="text-xs text-magenta-secondary leading-relaxed mb-4">
                Driving the user experience with clean, accessible interfaces. Committed to translating environmental vision into intuitive digital design.
            </p>

            {{-- Social Links --}}
            <div class="flex flex-wrap justify-center gap-2 mt-auto">
                <a href="#" class="inline-flex items-center text-sm text-dark-green border border-dark-green px-3 py-1 rounded-full hover:bg-light-green/20 transition">Email</a>
                <a href="#" class="inline-flex items-center text-sm text-dark-green border border-dark-green px-3 py-1 rounded-full hover:bg-light-green/20 transition">LinkedIn</a>
                <a href="#" class="inline-flex items-center text-sm text-dark-green border border-dark-green px-3 py-1 rounded-full hover:bg-light-green/20 transition">GitHub</a>
            </div>
        </div>
        
        {{-- TEAM MEMBER CARD 2 --}}
        <div class="bg-white p-6 rounded-xl border border-pink-logo/30 shadow-md flex flex-col items-center text-center">
            <img src="{{ asset('images/charlotte.jpg') }}" alt="Charlotte Rhyss Manuel" class="w-24 h-24 rounded-full object-cover border-4 border-light-green mb-4">
            
            <h3 class="font-bold text-dark-green text-lg">Charlotte Rhyss Manuel</h3>
            <p class="text-sm text-light-green font-semibold mb-3">Full-Stack Developer & Documentation</p>
            
            <p class="text-xs text-magenta-secondary leading-relaxed mb-4">
                Adept at bridging the front-end and back-end, focusing on performance, data integrity, and comprehensive documentation for sustainable growth.
            </p>

            <div class="flex flex-wrap justify-center gap-2 mt-auto">
                <a href="#" class="inline-flex items-center text-sm text-dark-green border border-dark-green px-3 py-1 rounded-full hover:bg-light-green/20 transition">Email</a>
                <a href="#" class="inline-flex items-center text-sm text-dark-green border border-dark-green px-3 py-1 rounded-full hover:bg-light-green/20 transition">LinkedIn</a>
                <a href="#" class="inline-flex items-center text-sm text-dark-green border border-dark-green px-3 py-1 rounded-full hover:bg-light-green/20 transition">GitHub</a>
            </div>
        </div>

        {{-- TEAM MEMBER CARD 3 --}}
        <div class="bg-white p-6 rounded-xl border border-pink-logo/30 shadow-md flex flex-col items-center text-center">
            <img src="{{ asset('images/mielle.jpg') }}" alt="Francesa Mielle Batoon" class="w-24 h-24 rounded-full object-cover border-4 border-light-green mb-4">
            
            <h3 class="font-bold text-dark-green text-lg">Francesa Mielle Batoon</h3>
            <p class="text-sm text-light-green font-semibold mb-3">Front-end Designer</p>
            
            <p class="text-xs text-magenta-secondary leading-relaxed mb-4">
                Focused on the aesthetic and visual branding of EcoSpaces, ensuring the platform's design is modern, engaging, and reflective of our green mission.
            </p>

            <div class="flex flex-wrap justify-center gap-2 mt-auto">
                <a href="#" class="inline-flex items-center text-sm text-dark-green border border-dark-green px-3 py-1 rounded-full hover:bg-light-green/20 transition">Email</a>
                <a href="#" class="inline-flex items-center text-sm text-dark-green border border-dark-green px-3 py-1 rounded-full hover:bg-light-green/20 transition">LinkedIn</a>
                <a href="#" class="inline-flex items-center text-sm text-dark-green border border-dark-green px-3 py-1 rounded-full hover:bg-light-green/20 transition">GitHub</a>
            </div>
        </div>

        {{-- TEAM MEMBER CARD 4 (Placeholder) --}}
        <div class="bg-white p-6 rounded-xl border border-pink-logo/30 shadow-md flex flex-col items-center text-center">
            <img src="{{ asset('images/bea.jpg') }}" alt="Bea Francesa Velasco" class="w-24 h-24 rounded-full object-cover border-4 border-light-green mb-4">
            
            <h3 class="font-bold text-dark-green text-lg">Bea Francesa Velasco</h3>
            <p class="text-sm text-light-green font-semibold mb-3">Full-Stack Developer</p>
            
            <p class="text-xs text-magenta-secondary leading-relaxed mb-4">
                Specializing in database architecture and backend services. Dedicated to building robust, scalable infrastructure that supports community interaction and resource mapping.
            </p>

            <div class="flex flex-wrap justify-center gap-2 mt-auto">
                <a href="#" class="inline-flex items-center text-sm text-dark-green border border-dark-green px-3 py-1 rounded-full hover:bg-light-green/20 transition">Email</a>
                <a href="#" class="inline-flex items-center text-sm text-dark-green border border-dark-green px-3 py-1 rounded-full hover:bg-light-green/20 transition">LinkedIn</a>
                <a href="#" class="inline-flex items-center text-sm text-dark-green border border-dark-green px-3 py-1 rounded-full hover:bg-light-green/20 transition">GitHub</a>
            </div>
        </div>

    </div>
</section>

    </main>

</x-app-layout>
