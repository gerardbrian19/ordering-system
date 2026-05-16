# UI Design Reference

## Design System

### Color Palette
| Role        | Tailwind Token     | Hex       |
|-------------|--------------------|-----------|
| Primary     | `indigo-600`       | #4F46E5   |
| Primary Hover | `indigo-700`     | #4338CA   |
| Accent      | `orange-500`       | #F97316   |
| Background  | `gray-50`          | #F9FAFB   |
| Surface     | `white`            | #FFFFFF   |
| Text Main   | `gray-900`         | #111827   |
| Text Muted  | `gray-500`         | #6B7280   |
| Border      | `gray-200`         | #E5E7EB   |
| Danger      | `red-600`          | #DC2626   |
| Success     | `green-600`        | #16A34A   |

### Typography
- Font: System UI stack (no Google Fonts needed)
- Headings: `font-bold` or `font-semibold`
- Body: `text-sm` to `text-base`, `text-gray-700`

### Spacing
- Page padding: `px-4 sm:px-6 lg:px-8`
- Card padding: `p-6`
- Section gap: `py-12` or `py-16`

---

## Component Patterns

### Navbar
- Sticky top, white bg, subtle shadow
- Logo left · Search center · Icons right (cart, user)
- Mobile: hamburger menu

### Cards (Product)
- Rounded corners (`rounded-xl`)
- Image top, info bottom
- Hover: slight lift (`hover:shadow-lg hover:-translate-y-1`)
- "Add to Cart" button on hover overlay

### Buttons
- Primary: `bg-indigo-600 text-white hover:bg-indigo-700 rounded-lg px-4 py-2`
- Outline: `border border-indigo-600 text-indigo-600 hover:bg-indigo-50`
- Danger: `bg-red-600 text-white hover:bg-red-700`

### Forms
- Labels above inputs
- Inputs: `border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500`
- Error state: `border-red-500` + error message below

### Badges / Tags
- Category badges: `bg-indigo-100 text-indigo-700 text-xs rounded-full px-2 py-0.5`
- Stock low: `bg-orange-100 text-orange-700`
- Out of stock: `bg-red-100 text-red-600`

---

## Page Layouts

### Login Page
```
┌─────────────────────────────────────┐
│           [Brand Logo + Name]        │
│                                      │
│  ┌───────────────────────────────┐   │
│  │  Sign in to your account      │   │
│  │                               │   │
│  │  Email ___________________    │   │
│  │  Password ______________🔑   │   │
│  │                               │   │
│  │  [Remember me]  [Forgot pwd?] │   │
│  │                               │   │
│  │  [    Sign In Button    ]     │   │
│  │                               │   │
│  │  Don't have an account?       │   │
│  │  → Register here              │   │
│  └───────────────────────────────┘   │
└─────────────────────────────────────┘
```

### Home Page
```
┌──────────────────────────────────────────┐
│  [Logo]   [Search Bar]   [Cart] [User]   │  ← Navbar
├──────────────────────────────────────────┤
│                                          │
│   ┌──────────────────────────────────┐   │
│   │  HERO BANNER (bg gradient)       │   │
│   │  "Discover Amazing Products"     │   │
│   │  [Shop Now]                      │   │
│   └──────────────────────────────────┘   │
│                                          │
│  Categories: [All] [Electronics] [...]   │
│                                          │
│  ┌────┐  ┌────┐  ┌────┐  ┌────┐         │
│  │ 📦 │  │ 📦 │  │ 📦 │  │ 📦 │  ← Products Grid
│  └────┘  └────┘  └────┘  └────┘         │
│                                          │
│  [Load More / Pagination]                │
├──────────────────────────────────────────┤
│                Footer                    │
└──────────────────────────────────────────┘
```

---

## Responsiveness
- Mobile-first approach
- Breakpoints: `sm` (640px), `md` (768px), `lg` (1024px), `xl` (1280px)
- Product grid: `grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4`
- Navbar collapses to hamburger on mobile
