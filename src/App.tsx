import { useState } from "react";
import "./App.css";

function App() {
  const [email, setEmail] = useState("");
  const [submitted, setSubmitted] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      setError("Enter a valid email address.");
      return;
    }
    setError("");
    setLoading(true);
    try {
      const res = await fetch(
        "https://accurate-diagnosis-api.onrender.com/api/v1/newsletter",
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ email, name: "user" }),
        },
      );
      if (!res.ok) throw new Error("subscribe_failed");
      setSubmitted(true);
    } catch {
      setError("Something went wrong. Please try again.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <main className="page">
      {/* Background */}
      <div className="bg-image" aria-hidden="true" />
      <div className="bg-overlay" aria-hidden="true" />

      {/* Content */}
      <div className="content">
        {/* Hero */}
        <section className="hero" aria-label="Coming soon">
          {/* Logo */}
          <header className="header">
            <img
              src="/logo-white.svg"
              alt="Healthgists"
              className="logo"
              width="180"
              height="36"
            />
          </header>

          <span className="badge">
            <span className="badge-dot" aria-hidden="true" />
            COMING SOON
          </span>

          <h1 className="headline">
            Your Trusted Source for <span className="accent">Health News</span>{" "}
            &amp; Expert Insights.
          </h1>

          <p className="subtext">
            Evidence-based health articles, wellness tips, and medical news,
            curated by professionals and delivered to you.
          </p>

          {/* Email signup */}
          {submitted ? (
            <div className="success" role="status">
              <svg
                width="20"
                height="20"
                viewBox="0 0 20 20"
                fill="none"
                aria-hidden="true"
              >
                <circle cx="10" cy="10" r="10" fill="#337D34" />
                <path
                  d="M5.5 10.5l3 3 6-6"
                  stroke="white"
                  strokeWidth="1.8"
                  strokeLinecap="round"
                  strokeLinejoin="round"
                />
              </svg>
              You're on the list! We'll notify you at launch.
            </div>
          ) : (
            <form className="signup-form" onSubmit={handleSubmit} noValidate>
              <p className="subtext subtext-sm">
                Be the first to know when we{" "}
                <span className="accent">launch</span>.
              </p>

              <div className="input-wrap">
                <input
                  type="email"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  placeholder="Enter your email address..."
                  className={`email-input${error ? " error" : ""}`}
                  aria-label="Email address"
                  aria-describedby={error ? "email-error" : undefined}
                  autoComplete="email"
                />
                <button type="submit" className="submit-btn" disabled={loading}>
                  {loading ? "Subscribing…" : "Notify Me"}
                </button>
              </div>
              {error && (
                <p id="email-error" className="error-msg" role="alert">
                  {error}
                </p>
              )}
            </form>
          )}

          {/* Social links */}
          <nav className="socials" aria-label="Social media">
            {/* <a
              href="https://x.com/healthgists"
              target="_blank"
              rel="noopener noreferrer"
              aria-label="Healthgists on X (Twitter)"
              className="social-link"
            >
              <svg
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="currentColor"
                aria-hidden="true"
              >
                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.746l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
              </svg>
            </a> */}
            <a
              href="https://web.facebook.com/profile.php?id=61589312589012"
              target="_blank"
              rel="noopener noreferrer"
              aria-label="Healthgists on Facebook"
              className="social-link"
            >
              <svg
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="currentColor"
                aria-hidden="true"
              >
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
              </svg>
            </a>
            <a
              href="https://www.instagram.com/healthgistsnigeria/"
              target="_blank"
              rel="noopener noreferrer"
              aria-label="Healthgists on Instagram"
              className="social-link"
            >
              <svg
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="currentColor"
                aria-hidden="true"
              >
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
              </svg>
            </a>
          </nav>
        </section>

        {/* Watermark wordmark */}
        <div className="watermark" aria-hidden="true">
          <img src="/logo-white.svg" alt="" className="watermark-img" />
        </div>

        {/* Footer */}
        <footer className="footer">
          <span>Copyright &copy; {new Date().getFullYear()} Healthgists</span>
          <span>
            Built by{" "}
            <a
              href="https://husnic.com"
              target="_blank"
              rel="noopener noreferrer"
              className="footer-link"
            >
              Husnic Consulting
            </a>
          </span>
        </footer>
      </div>
    </main>
  );
}

export default App;
