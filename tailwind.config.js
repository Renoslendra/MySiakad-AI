import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: [
                    "Plus Jakarta Sans",
                    "Inter",
                    ...defaultTheme.fontFamily.sans,
                ],
            },
            colors: {
                siakad: {
                    dark: "#1B3C53",
                    primary: "#234C6A",
                    secondary: "#456882",
                    light: "#E3E3E3",
                    accent: "#7C3AED",
                    50: "#f0f7fb",
                    100: "#dceef6",
                    200: "#b9dded",
                    300: "#86c5e0",
                    400: "#4da5cc",
                    500: "#234C6A",
                    600: "#1B3C53",
                    700: "#163247",
                    800: "#122839",
                    900: "#0d1e2b",
                },
            },
            boxShadow: {
                saas: "0 1px 3px 0 rgb(0 0 0 / 0.05), 0 1px 2px -1px rgb(0 0 0 / 0.05)",
                "saas-md":
                    "0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05)",
                "saas-lg":
                    "0 10px 15px -3px rgb(0 0 0 / 0.05), 0 4px 6px -4px rgb(0 0 0 / 0.05)",
                card: "0 0 0 1px rgb(0 0 0 / 0.03), 0 2px 4px rgb(0 0 0 / 0.05)",
                bento: "0 1px 2px rgba(0,0,0,.04), 0 4px 16px rgba(0,0,0,.06)",
                "bento-hover":
                    "0 2px 8px rgba(0,0,0,.06), 0 8px 32px rgba(0,0,0,.10)",
                "glow-violet":
                    "0 0 20px rgba(139,92,246,.25), 0 0 60px rgba(139,92,246,.10)",
                "glow-indigo":
                    "0 0 20px rgba(99,102,241,.25), 0 0 60px rgba(99,102,241,.10)",
                "glow-ai":
                    "0 0 15px rgba(139,92,246,.3), 0 0 45px rgba(99,102,241,.15), 0 0 80px rgba(139,92,246,.05)",
                glass: "0 8px 32px rgba(0,0,0,.08)",
                "glass-hover":
                    "0 12px 40px rgba(0,0,0,.12)",
            },
            borderRadius: {
                saas: "0.625rem",
                bento: "1.25rem",
            },
            backdropBlur: {
                xs: "2px",
            },
            animation: {
                "fade-in": "fadeIn 0.2s ease-out",
                "slide-in": "slideIn 0.2s ease-out",
                "slide-up": "slideUp 0.5s ease-out both",
                "gradient-x": "gradientX 3s ease infinite",
                shimmer: "shimmer 2.5s linear infinite",
                float: "float 6s ease-in-out infinite",
                "pulse-glow": "pulseGlow 2s ease-in-out infinite",
                "border-spin": "borderSpin 4s linear infinite",
                "glow-pulse": "glowPulse 3s ease-in-out infinite",
                "sparkle": "sparkle 1.5s ease-in-out infinite",
                "gradient-border": "gradientBorder 4s ease infinite",
            },
            keyframes: {
                fadeIn: {
                    "0%": { opacity: "0" },
                    "100%": { opacity: "1" },
                },
                slideIn: {
                    "0%": { opacity: "0", transform: "translateY(-4px)" },
                    "100%": { opacity: "1", transform: "translateY(0)" },
                },
                slideUp: {
                    "0%": { opacity: "0", transform: "translateY(16px)" },
                    "100%": { opacity: "1", transform: "translateY(0)" },
                },
                gradientX: {
                    "0%, 100%": { backgroundPosition: "0% 50%" },
                    "50%": { backgroundPosition: "100% 50%" },
                },
                shimmer: {
                    "0%": { transform: "translateX(-100%)" },
                    "100%": { transform: "translateX(100%)" },
                },
                float: {
                    "0%, 100%": { transform: "translateY(0px)" },
                    "50%": { transform: "translateY(-8px)" },
                },
                pulseGlow: {
                    "0%, 100%": { opacity: "0.4" },
                    "50%": { opacity: "1" },
                },
                borderSpin: {
                    "0%": { transform: "rotate(0deg)" },
                    "100%": { transform: "rotate(360deg)" },
                },
                glowPulse: {
                    "0%, 100%": {
                        boxShadow:
                            "0 0 15px rgba(139,92,246,.2), 0 0 45px rgba(99,102,241,.1)",
                    },
                    "50%": {
                        boxShadow:
                            "0 0 25px rgba(139,92,246,.4), 0 0 65px rgba(99,102,241,.2)",
                    },
                },
                sparkle: {
                    "0%, 100%": { opacity: "1", transform: "scale(1)" },
                    "50%": { opacity: "0.6", transform: "scale(0.85)" },
                },
                gradientBorder: {
                    "0%, 100%": { backgroundPosition: "0% 50%" },
                    "50%": { backgroundPosition: "100% 50%" },
                },
            },
        },
    },

    plugins: [forms],
};
