# Portfolio Project Backup & Technical Manual
**Date:** March 14, 2026
**Project:** Osman Nur Chaidir Portfolio & CMS

This document serves as a complete backup of our conversation progress and the technical state of the project.

## 1. Project Overview
A professional PHP portfolio with a custom CMS for managing every aspect of the website.

### Core Features:
- **Hero Section**: Dynamic image and slogan management.
- **Data & Facts**: Grid of numbers showing impact.
- **Organizations**: Display ventures with logos and roles.
- **Professional Journey**: Tabbed timeline (Work, Speaking, Writing) with clickable links.
- **Technical Skills**: Expanded categories (Digital Marketing, Business Mentor, Web Dev, Sociology, Others) with thumbnails.
- **Certifications & Achievements**: Tabbed section using external links.
- **News & Videos**: Infocards for press coverage.
- **Testimonials**: Auto-playing compact slider.
- **Service Ratecard**: Rich text descriptions (Quill.js).
- **Activity Slider**: 4:3 aspect ratio slider with hover titles.

## 2. Technical Stack
- **Backend**: Native PHP, MySQL.
- **Frontend**: HTML5, Vanilla CSS, JS.
- **Libraries**: Swiper.js, Quill.js.

## 3. Database Updates
Scripts to run in phpMyAdmin:
- `update_v2.sql`: News, Testimonials, Skill thumbnails.
- `update_v3.sql`: Category to Certifications.
- `update_v4.sql`: Link to Experiences.

## 4. SEO Configuration
- **Title**: Osman Nur Chaidir | Mentor Pengusaha Muda Berpengalaman 10 Tahun
- **Meta Description**: Berwirausaha sejak kelas 3 SMP, Osman Nur Chaidir kini telah membimbing lebih dari 150 pengusaha.
- **Open Graph**: Configured for `https://osmannurchaidir.ct.ws/`.

## 5. Troubleshooting Preview
Social media caches link previews. If the new title/desc doesn't show:
1. Go to [Facebook Sharing Debugger](https://developers.facebook.com/tools/debug/).
2. Enter your URL.
3. Click "Scrape Again".
4. WhatsApp will update shortly after.
