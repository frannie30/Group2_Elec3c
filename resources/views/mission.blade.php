
<x-app-layout>

    <style>
        :root{
            --brand-green: #18411a;
            --brand-green-light: #7bb25a;
            --muted-pink-copy: #7B5A61;
            --card-border-pink: rgba(247,183,200,0.18);
            --card-shadow: 0 10px 30px rgba(33,37,41,0.06);
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

        .sdg-badge {
            width: 56px;
            height: 56px;
            border-radius: 10px;
            background: var(--brand-green-light);
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

                <p class="text-xl text-green-700 font-light mb-10">
                    We help foster sustainable, community-first green spaces —
                    connecting people, events, and ideas to build healthier,
                    happier neighborhoods.
                </p>

                {{-- WHAT WE DO --}}
                <div class="mission-info text-left">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">What we do</h2>

                    <p class="text-gray-700 mb-4">
                        EcoSpaces builds a local ecosystem of eco-friendly places and events —
                        helping residents discover nearby parks, botanical gardens,
                        makerspaces, and sustainability-focused meetups. Our mission is to
                        nurture collaboration and environmental stewardship by making it
                        simple to find, share, and support green initiatives in your
                        community.
                    </p>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2">How we help</h3>

                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-6">
                        <li>Curate and list sustainable spaces and community-driven events.</li>
                        <li>Enable residents to leave reviews and ratings for transparency.</li>
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

                {{-- EXECUTIVE SUMMARY --}}
                <div class="max-w-5xl mx-auto mt-12 px-4">
                    <section class="panel">
                        <h4>Executive Summary</h4>

                        <p>
                            EcoSpaces is a comprehensive web-based directory that reconnects
                            urban residents with green spaces in Makati. In an increasingly
                            urbanized environment, many people lack access to nature and
                            meaningful community spaces. EcoSpaces addresses this by helping
                            residents discover parks, gardens, urban farms, and eco-friendly
                            venues — all while engaging in community-building activities such
                            as workshops, planting drives, and clean-up initiatives.
                        </p>

                        <p>
                            Through location-based discovery, event participation, reviews,
                            and community input, the platform builds a sustainable ecosystem
                            that promotes environmental stewardship and enhances mental
                            well-being for residents of all ages.
                        </p>
                    </section>
                </div>

                {{-- SDG SUMMARY --}}
                <div class="mt-14 text-center">
                    <h3 class="text-2xl font-extrabold text-dark-green mb-2">
                        Supporting the UN Sustainable Development Goals
                    </h3>

                    <p class="muted-copy max-w-3xl mx-auto leading-relaxed">
                        EcoSpaces is committed to advancing the United Nations SDGs through
                        community engagement and sustainable technology. We focus on the two
                        goals where we can create the strongest positive impact.
                        EcoSpaces aligns primarily with SDG 11 — Sustainable Cities &
                        Communities and SDG 3 — Good Health & Well-Being.
                    </p>

                    <div class="sdg-grid mt-6">

                        <div class="sdg-card">
                            <div class="sdg-badge">11</div>
                            <div>
                                <div class="font-semibold text-green-900 text-lg">
                                    Sustainable Cities & Communities
                                </div>
                                <div class="text-sm text-gray-700 mb-1">
                                    Promote accessible green areas & resilient urban design.
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
                            <div class="sdg-badge">3</div>
                            <div>
                                <div class="font-semibold text-green-900 text-lg">
                                    Good Health & Well-being
                                </div>
                                <div class="text-sm text-gray-700 mb-1">
                                    Encourage outdoor activity & mental wellness through
                                    nature access.
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

       <h4 class="mb-3">Sustainable Development Goals (SDG) Alignment</h4>

<p class="mt-3 mx-auto max-w-3xl text-center text-gray-700 leading-relaxed">
    By integrating space discovery, event participation, and community involvement,
    EcoSpaces empowers residents to take part in sustainable action while improving
    their own well-being. These efforts contribute to long-term resilience and
    healthier, greener urban communities across Makati.
</p>


    </section>
</div>

            </div>
        </section>
    </main>

</x-app-layout>
