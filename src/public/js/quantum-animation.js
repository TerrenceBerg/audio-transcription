class QuantumAnimation {
    constructor() {
        this.canvas = document.createElement('canvas');
        this.canvas.classList.add('quantum-background');
        document.body.insertBefore(this.canvas, document.body.firstChild);
        this.ctx = this.canvas.getContext('2d');

        this.config = {
            particleCount: 150,
            particleSpeed: 3,
            connectionDistance: 200,
            color: '0, 150, 255'
        };

        this.resize();
        this.init();
        window.addEventListener('resize', () => this.resize());
        this.animate();
    }

    resize() {
        this.width = window.innerWidth;
        this.height = window.innerHeight;
        this.canvas.width = this.width;
        this.canvas.height = this.height;

    }

    init() {

        this.particles = [];
        for (let i = 0; i < this.config.particleCount; i++) {
            this.particles.push({
                x: Math.random() * this.width,
                y: Math.random() * this.height,
                vx: (Math.random() - 0.5) * this.config.particleSpeed,
                vy: (Math.random() - 0.5) * this.config.particleSpeed,
                size: Math.random() * 2 + 2
            });
        }
    }

    draw() {
        // Clear canvas
        this.ctx.fillStyle = 'rgba(10, 15, 29, 0.2)';
        this.ctx.fillRect(0, 0, this.width, this.height);

        // Draw particles and connections
        this.particles.forEach(particle => {
            // Update position
            particle.x += particle.vx;
            particle.y += particle.vy;

            // Wrap around screen
            if (particle.x < 0) particle.x = this.width;
            if (particle.x > this.width) particle.x = 0;
            if (particle.y < 0) particle.y = this.height;
            if (particle.y > this.height) particle.y = 0;

            // Draw particle
            this.ctx.beginPath();
            this.ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
            this.ctx.fillStyle = `rgba(0, 150, 255, 0.8)`;
            this.ctx.fill();

            // Draw connections
            this.particles.forEach(other => {
                const dx = other.x - particle.x;
                const dy = other.y - particle.y;
                const distance = Math.sqrt(dx * dx + dy * dy);

                if (distance < this.config.connectionDistance) {
                    this.ctx.beginPath();
                    this.ctx.moveTo(particle.x, particle.y);
                    this.ctx.lineTo(other.x, other.y);
                    this.ctx.strokeStyle = `rgba(0, 150, 255, ${0.2 * (1 - distance/this.config.connectionDistance)})`;
                    this.ctx.stroke();
                }
            });
        });
    }

    animate() {
        this.draw();
        requestAnimationFrame(() => this.animate());
    }
}
