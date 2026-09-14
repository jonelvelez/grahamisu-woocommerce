<section class="bg-surface w-full overflow-hidden">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-[372px] pt-[80px] lg:pt-[107px] pb-[80px] lg:pb-[110px] flex flex-col items-center">

        <div class="gc-testimonial w-full" aria-live="polite" aria-label="Customer testimonials">

            <!-- Slides -->
            <div class="gc-testimonial__track relative">

                <?php
                $testimonials = [
                    [
                        'quote'   => '"Sobrang sarap! The layers are so perfect — creamy, not too sweet, and the espresso flavor is just right. Will definitely order again!"',
                        'name'    => 'Maria Santos',
                        'initial' => 'M',
                        'label'   => 'Solo Tub · Manila',
                    ],
                    [
                        'quote'   => '"Inorder ko para sa family reunion namin and lahat nagustuhan! Ang laki ng Satisfied tub but nakaubos pa rin namin. 10/10 talaga."',
                        'name'    => 'Jerome Reyes',
                        'initial' => 'J',
                        'label'   => "Satisfied · Quezon City",
                    ],
                    [
                        'quote'   => '"Best tiramisu I\'ve had in Manila — and I\'ve tried a lot. Ang fresh pa ng taste, parang galing talaga sa itaas na resto. Ordering the Super Cravings next time!"',
                        'name'    => 'Carla Dimaguiba',
                        'initial' => 'C',
                        'label'   => "Craving's · Makati",
                    ],
                    [
                        'quote'   => '"Pasalubong ko sa opisina — ubos agad in under 10 minutes. Ang daming nagtatanong kung saan galing. Sabi ko, Grahamisu lang yan!"',
                        'name'    => 'Paolo Villanueva',
                        'initial' => 'P',
                        'label'   => 'Super Cravings · BGC',
                    ],
                ];
                foreach ( $testimonials as $i => $t ) :
                ?>
                <div class="gc-testimonial__slide flex flex-col items-center <?php echo $i > 0 ? 'hidden' : ''; ?>"
                     role="group"
                     aria-label="Testimonial <?php echo $i + 1; ?> of <?php echo count( $testimonials ); ?>">

                    <p class="font-['Lato',sans-serif] font-normal text-[17px] text-[#f2b705] tracking-[3.06px] leading-none m-0 mb-6">
                        ★★★★★
                    </p>

                    <blockquote class="font-['Cormorant_Garamond',serif] italic font-semibold text-[24px] lg:text-[38px] leading-[1.5] text-[#2c1a0e] text-center max-w-[899px] m-0 p-0 border-none mb-8 lg:mb-[60px]">
                        <?php echo esc_html( $t['quote'] ); ?>
                    </blockquote>

                    <div class="flex items-center gap-4">
                        <div class="w-[60px] h-[60px] rounded-[30px] bg-brown flex items-center justify-center shrink-0">
                            <span class="font-['Cormorant_Garamond',serif] italic font-medium text-[34px] leading-none text-white">
                                <?php echo esc_html( $t['initial'] ); ?>
                            </span>
                        </div>
                        <div class="flex flex-col">
                            <p class="font-['Lato',sans-serif] italic font-bold text-[19px] leading-none text-[#2c1a0e] m-0 mb-1">
                                <?php echo esc_html( $t['name'] ); ?>
                            </p>
                            <p class="font-['Lato',sans-serif] font-normal text-[12px] leading-none text-brown tracking-[1.2px] uppercase m-0">
                                <?php echo esc_html( $t['label'] ); ?>
                            </p>
                        </div>
                    </div>

                </div>
                <?php endforeach; ?>

            </div>

            <!-- Dot navigation -->
            <div class="flex items-center justify-center gap-2 mt-10" role="tablist" aria-label="Select testimonial">
                <?php foreach ( $testimonials as $i => $t ) : ?>
                <button class="gc-testimonial__dot w-2 h-2 rounded-full transition-all duration-300 <?php echo $i === 0 ? 'bg-brown w-5' : 'bg-[#c9b8ad]'; ?>"
                        role="tab"
                        aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
                        aria-label="Testimonial <?php echo $i + 1; ?>"
                        data-index="<?php echo $i; ?>">
                </button>
                <?php endforeach; ?>
            </div>

        </div>

    </div>
</section>

<script>
(function () {
    var track  = document.querySelector('.gc-testimonial__track');
    if (!track) return;

    var slides = track.querySelectorAll('.gc-testimonial__slide');
    var dots   = document.querySelectorAll('.gc-testimonial__dot');
    var current = 0;
    var timer;

    function goTo(index) {
        slides[current].classList.add('hidden');
        dots[current].classList.remove('bg-brown', 'w-5');
        dots[current].classList.add('bg-[#c9b8ad]');
        dots[current].setAttribute('aria-selected', 'false');

        current = (index + slides.length) % slides.length;

        slides[current].classList.remove('hidden');
        dots[current].classList.add('bg-brown', 'w-5');
        dots[current].classList.remove('bg-[#c9b8ad]');
        dots[current].setAttribute('aria-selected', 'true');
    }

    function startTimer() {
        timer = setInterval(function () { goTo(current + 1); }, 5000);
    }

    function resetTimer() {
        clearInterval(timer);
        startTimer();
    }

    dots.forEach(function (dot) {
        dot.addEventListener('click', function () {
            goTo(parseInt(this.dataset.index, 10));
            resetTimer();
        });
    });

    startTimer();
}());
</script>
