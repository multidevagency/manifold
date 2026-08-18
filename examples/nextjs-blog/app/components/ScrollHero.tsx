'use client'

import { useEffect, useRef, useState } from 'react'

const SMOOTHING = 0.22

export default function ScrollHero() {
  const containerRef = useRef<HTMLDivElement>(null)
  const videoRef = useRef<HTMLVideoElement>(null)
  const progressRef = useRef(0)
  const [mounted, setMounted] = useState(false)
  const [revealed, setRevealed] = useState(false)

  useEffect(() => {
    setMounted(true)
    const video = videoRef.current
    if (video) video.pause()

    let raf = 0
    let target = 0

    const onScroll = () => {
      const container = containerRef.current
      if (!container) return
      const rect = container.getBoundingClientRect()
      const scrollable = rect.height - window.innerHeight
      target = Math.min(1, Math.max(0, -rect.top / scrollable))
    }

    const tick = () => {
      const video = videoRef.current
      if (video && video.duration) {
        const targetTime = target * video.duration
        // Lerp instead of assigning: seeks land on the nearest keyframe, so a
        // glide hides the stepping that a direct snap makes visible.
        const next = video.currentTime + (targetTime - video.currentTime) * SMOOTHING
        if (Math.abs(next - video.currentTime) > 0.001) video.currentTime = next
      }
      progressRef.current = target
      setRevealed(target > 0.65)
      raf = requestAnimationFrame(tick)
    }

    onScroll()
    window.addEventListener('scroll', onScroll, { passive: true })
    raf = requestAnimationFrame(tick)

    return () => {
      window.removeEventListener('scroll', onScroll)
      cancelAnimationFrame(raf)
    }
  }, [])

  return (
    <div
      ref={containerRef}
      style={{
        height: '300vh',
        position: 'relative',
        width: '100vw',
        marginLeft: 'calc(50% - 50vw)',
        marginTop: '-3rem',
        marginBottom: '3rem',
      }}
    >
      <div style={{ position: 'sticky', top: 0, height: '100vh', overflow: 'hidden' }}>
        <video
          ref={videoRef}
          src="/hero.mp4"
          poster="/hero-poster.jpg"
          muted
          playsInline
          preload="auto"
          style={{
            width: '100vw',
            height: '100vh',
            objectFit: 'cover',
            opacity: mounted ? 1 : 0,
            transition: 'opacity 200ms ease',
          }}
        />

        <div
          style={{
            position: 'absolute',
            inset: 0,
            display: 'flex',
            flexDirection: 'column',
            alignItems: 'center',
            justifyContent: 'center',
            textAlign: 'center',
            color: '#f2efe6',
            background: revealed ? 'rgba(15, 10, 5, 0.45)' : 'transparent',
            opacity: revealed ? 1 : 0,
            transition: 'opacity 600ms ease, background 600ms ease',
            pointerEvents: 'none',
          }}
        >
          <p style={{ fontFamily: 'system-ui', letterSpacing: '0.3em', fontSize: '0.8rem' }}>POWERED BY MANIFOLD</p>
          <h1 style={{ fontFamily: 'system-ui', fontWeight: 900, fontSize: 'clamp(2.5rem, 7vw, 6rem)', margin: '0.2em 0' }}>
            MANIFOLD
          </h1>
          <p style={{ fontFamily: 'Georgia, serif', fontStyle: 'italic', opacity: 0.85 }}>
            Keep scrolling — the site is right below.
          </p>
        </div>

        <div
          style={{
            position: 'absolute',
            bottom: '2rem',
            left: '50%',
            transform: 'translateX(-50%)',
            color: '#f2efe6',
            fontFamily: 'monospace',
            fontSize: '0.75rem',
            letterSpacing: '0.2em',
            opacity: revealed ? 0 : 0.8,
            transition: 'opacity 400ms ease',
          }}
        >
          SCROLL ▾
        </div>
      </div>
    </div>
  )
}
