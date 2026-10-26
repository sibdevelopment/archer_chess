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
@endif
