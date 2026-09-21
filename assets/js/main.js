// Mobile nav — full-screen popover, not an inline dropdown.
document.addEventListener("DOMContentLoaded", () => {
  const toggle = document.querySelector("[data-nav-toggle]");
  const close = document.querySelector("[data-nav-close]");
  const menu = document.querySelector("[data-nav-menu]");
  if (toggle && menu) {
    const open = () => {
      menu.classList.remove("hidden");
      menu.classList.add("flex");
      toggle.setAttribute("aria-expanded", "true");
      document.body.classList.add("overflow-hidden");
    };
    const dismiss = () => {
      menu.classList.add("hidden");
      menu.classList.remove("flex");
      toggle.setAttribute("aria-expanded", "false");
      document.body.classList.remove("overflow-hidden");
    };
    toggle.addEventListener("click", open);
    if (close) close.addEventListener("click", dismiss);
    menu.querySelectorAll("[data-nav-link]").forEach((link) => link.addEventListener("click", dismiss));
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") dismiss();
    });
  }

  // Hero slider: crossfade full slides (photo + text together per slide).
  var heroWrap = document.querySelector("[data-hero-slides]");
  if (heroWrap) {
    var slides = heroWrap.querySelectorAll("[data-hero-slide]");
    var dots = document.querySelectorAll("[data-hero-dot]");
    var current = 0;

    var showSlide = function (index) {
      slides.forEach(function (slide, i) {
        slide.classList.toggle("opacity-100", i === index);
        slide.classList.toggle("opacity-0", i !== index);
        slide.classList.toggle("pointer-events-none", i !== index);
      });
      dots.forEach(function (dot, i) {
        dot.classList.toggle("w-6", i === index);
        dot.classList.toggle("bg-gold", i === index);
        dot.classList.toggle("w-1.5", i !== index);
        dot.classList.toggle("bg-white/40", i !== index);
      });
      current = index;
    };

    dots.forEach(function (dot, i) {
      dot.addEventListener("click", function () {
        showSlide(i);
      });
    });

    if (slides.length > 1) {
      setInterval(function () {
        showSlide((current + 1) % slides.length);
      }, 5500);
    }
  }

  // Newsletter signup — same endpoint the coming-soon page used.
  document.querySelectorAll("[data-newsletter-form]").forEach((form) => {
    const input = form.querySelector("input[type=email]");
    const button = form.querySelector("button[type=submit]");
    const status = form.querySelector("[data-newsletter-status]");

    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      const email = input.value.trim();
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        status.textContent = "Enter a valid email address.";
        status.classList.remove("hidden", "text-green");
        status.classList.add("text-red-600");
        return;
      }
      button.disabled = true;
      button.textContent = "Subscribing…";
      try {
        const res = await fetch(
          "https://accurate-diagnosis-api.onrender.com/api/v1/newsletter",
          {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ email, name: "user" }),
          }
        );
        if (!res.ok) throw new Error("subscribe_failed");
        status.textContent = "You're subscribed. Welcome to Healthgists!";
        status.classList.remove("hidden", "text-red-600");
        status.classList.add("text-green");
        form.reset();
      } catch {
        status.textContent = "Something went wrong. Please try again.";
        status.classList.remove("hidden", "text-green");
        status.classList.add("text-red-600");
      } finally {
        button.disabled = false;
        button.textContent = "Subscribe";
      }
    });
  });
});
