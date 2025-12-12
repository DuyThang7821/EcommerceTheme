<?php

/**
 * Reusable Section Tag Component
 * Usage: exclusive_section_tag('Today\'s', 'Flash Sales');
 */

function exclusive_section_tag($subtitle, $title, $show_arrows = false)
{
?>
    <div class="section-header-wrapper <?php echo $show_arrows ? 'with-arrows' : ''; ?>">
        <div class="section-tag-group">
            <div class="section-tag">
                <?php echo esc_html($subtitle); ?>
            </div>
            <h2 class="section-title"><?php echo esc_html($title); ?></h2>
        </div>

        <?php if ($show_arrows): ?>
            <div class="section-nav-arrows">
                <button class="nav-arrow prev-arrow" aria-label="Previous">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <button class="nav-arrow next-arrow" aria-label="Next">
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        <?php endif; ?>
    </div>

    <style>
        .section-header-wrapper {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 40px;
            gap: 20px;
        }

        .section-tag-group {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .section-tag {
            display: flex;
            align-items: center;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 16px;
            position: relative;
        }

        .section-tag::before {
            content: "";
            width: 20px;
            height: 40px;
            background: var(--primary-color);
            display: inline-block;
            margin-right: 16px;
            border-radius: 4px;
        }

        .section-title {
            font-size: 36px;
            font-weight: 600;
            margin: 0;
            color: #000;
            line-height: 1.2;
        }

        .section-nav-arrows {
            display: flex;
            gap: 8px;
        }

        .nav-arrow {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: #F5F5F5;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #000;
        }

        .nav-arrow:hover {
            background: var(--primary-color);
            color: #fff;
            transform: scale(1.05);
        }

        .nav-arrow i {
            font-size: 16px;
        }

        @media (max-width: 767px) {
            .section-header-wrapper {
                flex-direction: column;
                align-items: flex-start;
                margin-bottom: 24px;
            }

            .section-tag::before {
                width: 16px;
                height: 32px;
                margin-right: 12px;
            }

            .section-tag {
                font-size: 14px;
            }

            .section-title {
                font-size: 24px;
            }

            .section-tag-group {
                gap: 16px;
            }

            .nav-arrow {
                width: 40px;
                height: 40px;
            }
        }
    </style>
<?php
}
