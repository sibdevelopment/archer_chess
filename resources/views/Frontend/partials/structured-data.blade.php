<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "EducationalOrganization",
    "@id": "https://www.archerchessacademy.com/#organization",
    "name": "Archer Chess Academy",
    "url": "https://www.archerchessacademy.com/",
    "logo": "https://www.archerchessacademy.com/frontend1/tcul-img/img/archer-new-logo.png",
    "sameAs": [
        "https://www.facebook.com/makekidssmarter",
        "https://www.instagram.com/archerchessacademy/",
        "https://www.youtube.com/@archerchessacademy88",
        "https://www.linkedin.com/company/archer-chess-academy/"
    ]
}
</script>

@if (request()->routeIs('home'))
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "@id": "https://www.archerchessacademy.com/#website",
        "name": "Archer Chess Academy",
        "url": "https://www.archerchessacademy.com/",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "https://www.archerchessacademy.com/search?q={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Person",
        "@id": "https://www.archerchessacademy.com/#thejkumar-ms",
        "name": "Thejkumar M. S.",
        "honorificPrefix": "GM",
        "jobTitle": "Grandmaster Coach",
        "worksFor": {
            "@id": "https://www.archerchessacademy.com/#organization"
        },
        "sameAs": [
            "https://ratings.fide.com/profile/5009154",
            "https://en.wikipedia.org/wiki/Thejkumar_M._S."
        ]
    }
    </script>

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            {
                "@type": "Question",
                "name": "How do I enroll my child in online chess classes at Archer Chess Academy?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Book a free trial class on our website, then choose the course level that fits your child (beginner, intermediate or expert) and complete the registration form. Our team will guide you through each step."
                }
            },
            {
                "@type": "Question",
                "name": "What makes Archer Chess Academy different from other online chess academies?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "We don’t just teach moves, we build thinkers. Our FIDE-rated coaches teach tactics, strategy and decision-making through a structured curriculum curated by Grandmaster Thejkumar M. S. Classes are small and fun, so every child gets personal attention."
                }
            },
            {
                "@type": "Question",
                "name": "What age groups can join your chess classes?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "We teach online chess classes for kids aged 5 to 12 and also welcome older learners and adults. The curriculum and teaching style are matched to each student’s age and skill level."
                }
            },
            {
                "@type": "Question",
                "name": "Will my child get enough attention in group chess classes?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Yes. We keep batches small so every student gets guidance. If you prefer, we also offer one-on-one online chess lessons for focused learning."
                }
            },
            {
                "@type": "Question",
                "name": "Do you prepare students for chess tournaments?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Yes. We train students in opening repertoire, time management and handling pressure, so they feel ready for online and over-the-board tournaments."
                }
            }
        ]
    }
    </script>
@endif
