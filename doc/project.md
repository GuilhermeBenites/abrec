# ABREC Pacientes

## Project Goal

A small web system to help an ONG (non-governmental organization) register and manage their patients.

## Core Functionality

- **Patient Registration**: Form to register new patients with personal data (name, CPF, date of birth, gender, address) and health information (weight, height, blood pressure, blood glucose, creatinine, and health conditions like diabetes, hypertension, kidney problems, family DRC history, obesity, and fundoscopy exam).
- **Patient List**: View all registered patients in a paginated, searchable, and filterable table.
- **Excel Export**: Export the patient list to an Excel file.

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.2)
- **Frontend**: Vue 3 + Inertia.js v2
- **Styling**: Tailwind CSS v4 with a red-branded medical theme (primary color: #D92D2D, font: Inter)
- **Testing**: Pest 3
- **Routing**: Laravel Wayfinder for type-safe route generation in TypeScript

## Design System

The prototypes are located in `doc/prototypes/` and define the visual standards:

- **Primary color**: #D92D2D (red), hover: #B91C1C
- **Font**: Inter (300-700)
- **Light background**: #F3F4F6, surfaces: #FFFFFF, inputs: #F9FAFB
- **Dark background**: #111827, surfaces: #1F2937, inputs: #374151
- **Border radius**: 0.5rem default
- **Icons**: Material Icons / Material Symbols
