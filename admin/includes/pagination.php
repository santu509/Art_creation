<?php
/**
 * Reusable Pagination & Page-Size Selector Component
 * Siddha Art Creation Admin Panel
 */

/**
 * Render Top Page-Size Selector Dropdown ("Showing [ 10 ˅ ] Result")
 * 
 * @param int $perPage Currently selected per-page limit
 * @param array $options List of per-page options [5, 10, 25, 50, 100]
 * @param string $onchangeJS JS function call on change (default: 'changePerPage(this.value)')
 */
function renderPageSizeSelector($perPage = 10, $options = [5, 10, 25, 50, 100], $onchangeJS = 'changePerPage(this.value)') {
    ?>
    <div class="page-size-selector-wrapper">
        <span class="page-size-label">Showing</span>
        <select class="form-select-per-page" onchange="<?php echo htmlspecialchars($onchangeJS); ?>" title="Select items per page">
            <?php foreach ($options as $opt): ?>
                <option value="<?php echo $opt; ?>" <?php echo ((int)$perPage === (int)$opt) ? 'selected' : ''; ?>>
                    <?php echo $opt; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <span class="page-size-label">Result</span>
    </div>
    <style>
        .page-size-selector-wrapper {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #7C7267;
            font-weight: 500;
        }

        .form-select-per-page {
            padding: 6px 30px 6px 12px;
            border-radius: 10px;
            background-color: #FAF8F5;
            border: 1.5px solid rgba(212, 175, 55, 0.3);
            color: #2A241D;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            outline: none;
            transition: all 0.2s ease;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%3cB8860B' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 11px 11px;
            box-shadow: 0 2px 6px rgba(184, 134, 11, 0.05);
        }

        .form-select-per-page:focus, .form-select-per-page:hover {
            border-color: #D4AF37;
            background-color: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
        }
    </style>
    <?php
}

/**
 * Render Bottom Pagination Controls ("Showing 1 to 5 of 13 entries" + Page Buttons [1][2][3][>])
 * 
 * @param int $totalPages Total number of pages
 * @param int $currentPage Current active page number (1-indexed)
 * @param int $totalRecords Total count of records
 * @param int $perPage Items per page limit
 * @param array $queryParams URL Query parameters to preserve in links
 * @param string $entityName Singular/plural label for records e.g. "entries" or "customers"
 */
function renderPagination($totalPages, $currentPage, $totalRecords, $perPage, $queryParams = [], $entityName = 'entries') {
    if ($totalRecords == 0) {
        return;
    }

    $startRecord = (($currentPage - 1) * $perPage) + 1;
    $endRecord = min($startRecord + $perPage - 1, $totalRecords);

    // Closure to construct page URLs dynamically preserving current filters
    $buildUrl = function($page) use ($queryParams) {
        $params = array_merge($queryParams, ['page' => $page]);
        return '?' . http_build_query($params);
    };
    ?>
    <div class="pagination-wrapper">
        <!-- Summary Info Left -->
        <div class="pagination-info">
            Showing <?php echo $startRecord; ?> to <?php echo $endRecord; ?> of <?php echo $totalRecords; ?> <?php echo htmlspecialchars($entityName); ?>
        </div>

        <!-- Page Buttons Right -->
        <?php if ($totalPages > 1): ?>
        <ul class="pagination-custom">
            <!-- Previous Page Button -->
            <li class="page-item-custom <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                <a href="<?php echo ($currentPage > 1) ? $buildUrl($currentPage - 1) : '#'; ?>" 
                   onclick="<?php echo ($currentPage > 1) ? 'if(typeof triggerSearch === \'function\'){ event.preventDefault(); triggerSearch(' . ($currentPage - 1) . '); }' : ''; ?>"
                   class="page-link-custom" aria-label="Previous">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
            </li>

            <?php
            $range = 2;
            $startPage = max(1, $currentPage - $range);
            $endPage = min($totalPages, $currentPage + $range);

            if ($startPage > 1) {
                echo '<li class="page-item-custom"><a href="' . $buildUrl(1) . '" onclick="if(typeof triggerSearch===\'function\'){event.preventDefault();triggerSearch(1);}" class="page-link-custom">1</a></li>';
                if ($startPage > 2) {
                    echo '<li class="page-item-custom disabled"><span class="page-link-custom">&hellip;</span></li>';
                }
            }

            for ($i = $startPage; $i <= $endPage; $i++) {
                $activeClass = ($i == $currentPage) ? 'active' : '';
                echo '<li class="page-item-custom ' . $activeClass . '"><a href="' . $buildUrl($i) . '" onclick="if(typeof triggerSearch===\'function\'){event.preventDefault();triggerSearch(' . $i . ');}" class="page-link-custom">' . $i . '</a></li>';
            }

            if ($endPage < $totalPages) {
                if ($endPage < $totalPages - 1) {
                    echo '<li class="page-item-custom disabled"><span class="page-link-custom">&hellip;</span></li>';
                }
                echo '<li class="page-item-custom"><a href="' . $buildUrl($totalPages) . '" onclick="if(typeof triggerSearch===\'function\'){event.preventDefault();triggerSearch(' . $totalPages . ');}" class="page-link-custom">' . $totalPages . '</a></li>';
            }
            ?>

            <!-- Next Page Button -->
            <li class="page-item-custom <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
                <a href="<?php echo ($currentPage < $totalPages) ? $buildUrl($currentPage + 1) : '#'; ?>" 
                   onclick="<?php echo ($currentPage < $totalPages) ? 'if(typeof triggerSearch === \'function\'){ event.preventDefault(); triggerSearch(' . ($currentPage + 1) . '); }' : ''; ?>"
                   class="page-link-custom" aria-label="Next">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            </li>
        </ul>
        <?php endif; ?>
    </div>

    <style>
        .pagination-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            padding: 16px 24px;
            background-color: #FFFFFF;
            border-top: 1px solid rgba(212, 175, 55, 0.2);
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 16px;
        }

        .pagination-info {
            font-size: 13px;
            color: #7C7267;
            font-weight: 500;
        }

        .pagination-custom {
            display: flex;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 6px;
        }

        .page-item-custom {
            margin: 0;
        }

        .page-link-custom {
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background-color: #FAF8F5;
            border: 1px solid rgba(212, 175, 55, 0.25);
            color: #2A241D;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .page-link-custom:hover {
            background-color: rgba(212, 175, 55, 0.15);
            color: #B8860B;
            border-color: #D4AF37;
        }

        .page-item-custom.active .page-link-custom {
            background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 50%, #9B781E 100%);
            color: #1A1612;
            border-color: #B8860B;
            box-shadow: 0 3px 10px rgba(184, 134, 11, 0.25);
            font-weight: 700;
        }

        .page-item-custom.disabled .page-link-custom {
            opacity: 0.45;
            cursor: not-allowed;
            pointer-events: none;
            background-color: #F5F2ED;
        }

        @media (max-width: 576px) {
            .pagination-wrapper {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
        }
    </style>
    <?php
}
?>
