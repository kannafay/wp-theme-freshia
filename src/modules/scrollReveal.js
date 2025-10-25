import GsapScrollReveal from '@/utils/GsapScrollReveal.js'

export default function scrollReveal() {
    return new GsapScrollReveal([
        {
            selector: '.scroll-fade-up',
            from: { y: 50 },
            to: { y: 0 },
            duration: 1,
            ease: 'expo.inOut',
        },
        {
            selector: '.scroll-fade-down',
            from: { y: -50 },
            to: { y: 0 },
            duration: 1,
            ease: 'expo.inOut',
        },
    ])
}