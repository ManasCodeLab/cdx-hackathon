# CodeGenX Hackathon Landing Page

A modern, responsive landing page for the CodeGenX Hackathon event. Built with vanilla HTML, CSS, and JavaScript using Tailwind CSS for styling.

## Features

- 🎨 Modern dark theme with neon accents
- 📱 Fully responsive design
- ⏱️ Live countdown timer
- 📝 Registration form with coupon system
- 📅 Event schedule timeline
- ❓ Interactive FAQ section
- 🏆 Prize information

## Tech Stack

- **HTML5** - Semantic markup
- **CSS3** - Custom styling with Tailwind CSS (via CDN)
- **JavaScript** - Interactive functionality
- **Google Fonts** - Inter, JetBrains Mono, and Poppins fonts

## Getting Started

### Prerequisites

- Node.js (version 14 or higher)
- npm (comes with Node.js)

### Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd codegenx-hackathon
   ```

2. Install dependencies:
   ```bash
   npm install
   ```

3. Start the development server:
   ```bash
   npm start
   ```

   The site will be available at `http://localhost:3000`

### Available Scripts

- `npm start` - Start the development server
- `npm run dev` - Alternative command to start development server
- `npm run serve` - Serve the static files
- `npm run build` - No build process needed (static site)

## Project Structure

```
codegenx-hackathon/
├── index.html          # Main HTML file
├── package.json        # Node.js dependencies and scripts
├── README.md          # Project documentation
└── .gitignore         # Git ignore rules
```

## Development

This is a static HTML site that uses:

- **Tailwind CSS** loaded via CDN for styling
- **Google Fonts** for typography
- **Simple Icons** for social media icons
- Vanilla JavaScript for interactivity

### Key Features Implementation

- **Countdown Timer**: Real-time countdown to hackathon start date
- **Registration Form**: Team/solo toggle with coupon code system
- **FAQ Accordion**: Collapsible Q&A sections
- **Mobile Menu**: Responsive navigation
- **Dark Theme**: Built-in dark mode styling

## Customization

To customize the design:

1. **Colors**: Modify the Tailwind config in the `<script>` tag within `index.html`
2. **Content**: Update text directly in the HTML
3. **Fonts**: Change font imports in the `<head>` section
4. **JavaScript**: Modify the script section at the bottom of `index.html`

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

MIT License - see LICENSE file for details

## Contact

Created by [Manas Arora](https://linkedin.com/in/aroramanas01/)

For questions or support, please open an issue in the repository.
