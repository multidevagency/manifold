'use client'

import { useRef } from 'react'
import { motion, useScroll, useTransform } from 'motion/react'
import { Facebook, Twitter, Instagram, Linkedin } from 'lucide-react'

const DEFAULT_COLUMNS = [
  { heading: 'Company', links: [{ label: 'Founding', url: '#' }, { label: 'Platform', url: '#' }, { label: 'Testify', url: '#' }] },
  { heading: 'Mobile', links: [{ label: 'Get Apple App', url: '#' }, { label: 'Get Google App', url: '#' }] },
  { heading: 'Contracts', links: [{ label: 'Private Data', url: '#' }, { label: 'User Consent', url: '#' }] },
]

const SOCIALS = [Facebook, Twitter, Instagram, Linkedin]

const BG_URL =
  'https://images.higgs.ai/?default=1&output=webp&url=https%3A%2F%2Fd8j0ntlcm91z4.cloudfront.net%2Fuser_38xzZboKViGWJOttwIXH07lWA1P%2Fhf_20260430_115327_3f256636-9e63-4885-8d0b-09317dc2b0a5.png&w=1280&q=85'

const TRUCK_URL =
  'https://roof-wish-40038865.figma.site/_components/v2/f31fd17907ce60745d45e83a61d44fd3810d5f25/truck_1.8c4bff83.png'

export default function HaulFooter({ brand = 'HAUL!', links = [], copyright = '© 2026 HAUL! All Rights Reserved' }) {
  const columns = links.length
    ? [...DEFAULT_COLUMNS.slice(0, 2), { heading: 'Links', links }]
    : DEFAULT_COLUMNS

  const containerRef = useRef(null)
  const { scrollYProgress } = useScroll({ target: containerRef })
  const truckY = useTransform(scrollYProgress, [0, 1], [-50, 150])

  return (
    <div className="bg-[#f8f9fa]" style={{ fontFamily: 'var(--font-inter)' }}>
      <section
        ref={containerRef}
        className="relative h-screen overflow-hidden bg-cover bg-center"
        style={{ backgroundImage: `url(${BG_URL})` }}
      >
        <div className="absolute top-0 w-full pt-12 md:pt-24 lg:pt-12">
          <motion.div
            initial={{ opacity: 0, y: -20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8, ease: 'easeOut' }}
            className="mx-auto max-w-7xl overflow-hidden rounded-2xl bg-white/95 shadow-xl backdrop-blur-sm md:rounded-3xl"
          >
            <div className="flex flex-col justify-between gap-10 p-8 md:flex-row md:p-12">
              <div className="flex items-center gap-3">
                <div className="h-10 w-10 rounded-lg bg-orange-500 p-2 shadow-inner md:h-12 md:w-12">
                  <svg viewBox="0 0 256 256" className="h-full w-full">
                    <path
                      fill="#fff"
                      d="M 228 0 C 172.772 0 128 44.772 128 100 L 128 0 L 0 0 L 0 28 C 0 83.228 44.772 128 100 128 L 0 128 L 0 256 L 28 256 C 83.228 256 128 211.228 128 156 L 128 256 L 256 256 L 256 228 C 256 172.772 211.228 128 156 128 L 256 128 L 256 0 Z"
                    />
                  </svg>
                </div>
                <span className="text-2xl font-bold tracking-tighter text-gray-900 md:text-3xl">{brand}</span>
              </div>

              <div className="flex flex-col gap-10 sm:flex-row sm:gap-16">
                {columns.map((column) => (
                  <div key={column.heading}>
                    <p className="mb-3 text-sm font-bold uppercase tracking-widest text-gray-900">{column.heading}</p>
                    <ul className="space-y-2">
                      {column.links.map((link) => (
                        <li key={link.label}>
                          <a href={link.url} className="font-medium text-gray-500 transition hover:text-orange-600">
                            {link.label}
                          </a>
                        </li>
                      ))}
                    </ul>
                  </div>
                ))}
              </div>
            </div>

            <div className="flex flex-col items-center justify-between gap-4 border-t border-gray-100 bg-white px-8 py-5 md:flex-row md:px-12">
              <p className="text-sm font-medium text-gray-500">{copyright}</p>
              <div className="flex gap-3">
                {SOCIALS.map((Icon, i) => (
                  <a
                    key={i}
                    href="#"
                    className="flex h-10 w-10 items-center justify-center rounded-full border border-gray-100 text-gray-600 transition-all duration-300 hover:border-orange-500 hover:bg-orange-500 hover:text-white"
                  >
                    <Icon className="h-5 w-5" />
                  </a>
                ))}
              </div>
            </div>
          </motion.div>
        </div>

        <motion.div style={{ y: truckY }} className="pointer-events-none absolute inset-x-0 bottom-0 z-20 h-full">
          <img
            src={TRUCK_URL}
            alt=""
            className="h-full w-full origin-bottom scale-[1.5] object-contain object-bottom sm:scale-110 md:scale-[2.0] lg:scale-105"
          />
        </motion.div>
      </section>
    </div>
  )
}
