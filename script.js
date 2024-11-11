// Function to replace null values with an empty string
function replaceNull(value) {
    return value === null ? '' : value;
}

// Function to show/hide tables based on selected year and semester
function showTable(year, semesters) {
    // Convert single semester to an array if necessary
    if (!Array.isArray(semesters)) {
        semesters = [semesters];
    }

    // Hide all tables
    $('.tableContainer').hide();
    
    // Show tables for the selected year and semesters
    semesters.forEach(semester => {
        $(`.tableContainer:has(h2:contains(${year})):has(h3:contains(${semester}))`).show();
    });
}

const searchInput = document.getElementById('searchInput');
const containers = document.querySelectorAll('.tableContainer');
const errorMessage = document.getElementById('errorMessage');

searchInput.addEventListener('input', function() {
    const searchText = this.value.trim().toLowerCase();
    
    if (searchText === '') {
        // If search input is empty, display a message
        errorMessage.textContent = 'Search bar is empty';
        errorMessage.style.display = 'block';
        resetContainers(containers);
    } else {
        filterContainers(containers, searchText);
    }
});

function resetContainers(containers) {
    containers.forEach(container => {
        container.style.display = '';
        const tables = container.querySelectorAll('.checklistTable');
        tables.forEach(table => {
            table.style.display = '';
            const rows = table.getElementsByTagName('tr');
            for (let i = 1; i < rows.length; i++) {
                rows[i].style.display = '';
                const cells = rows[i].getElementsByTagName('td');
                for (let j = 0; j < cells.length; j++) {
                    cells[j].innerHTML = cells[j].textContent; // Remove highlights
                }
            }
        });
    });
}

function filterContainers(containers, searchText) {
    let hasVisibleRows = false;
    containers.forEach(container => {
        const tables = container.querySelectorAll('.checklistTable');
        let containerHasVisibleRows = false;

        tables.forEach(table => {
            const rows = table.getElementsByTagName('tr');
            let tableHasVisibleRows = false;

            for (let i = 1; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                let found = false;

                for (let j = 0; j < cells.length; j++) {
                    const cellText = cells[j].textContent.trim().toLowerCase();
                    const indexOfMatch = cellText.indexOf(searchText);

                    if (indexOfMatch !== -1) {
                        found = true;
                        const start = indexOfMatch;
                        const end = start + searchText.length;
                        const before = cells[j].textContent.substring(0, start);
                        const match = cells[j].textContent.substring(start, end);
                        const after = cells[j].textContent.substring(end);

                        cells[j].innerHTML = before + '<span class="highlight">' + match + '</span>' + after;
                    }
                }

                if (found) {
                    rows[i].style.display = '';
                    tableHasVisibleRows = true;
                    containerHasVisibleRows = true;
                    hasVisibleRows = true;
                } else {
                    rows[i].style.display = 'none';
                }
            }

            if (tableHasVisibleRows) {
                table.style.display = '';
            } else {
                table.style.display = 'none';
            }
        });

        if (containerHasVisibleRows) {
            container.style.display = '';
        } else {
            container.style.display = 'none';
        }
    });

    if (!hasVisibleRows) {
        errorMessage.textContent = 'No matching results found';
        errorMessage.style.display = 'block';
    } else {
        errorMessage.style.display = 'none';
    }
}

$(document).ready(function(){
    // jQuery for toggle sub menus
    $('.sub-btn').click(function(e){
        e.preventDefault(); // Prevent default link behavior
        $(this).next('.sub-menu').slideToggle();
        $(this).find('.dropdown').toggleClass('rotate');
    });

    // jQuery for expand and collapse the sidebar
    $('.menu-btn').click(function(){
        $('.side-bar').toggleClass('active');
        $('.menu-btn').toggleClass('active');
        if ($('.side-bar').hasClass('active')) {
            $('#mainContainer').addClass('active');
        } else {
            $('#mainContainer').removeClass('active');
        }
    });

    $('.close-btn').click(function(){
        $('.side-bar').removeClass('active');
        $('.menu-btn').removeClass('active');
        $('#mainContainer').removeClass('active');
        // Smoothly return mainContainer to its original position
        setTimeout(function() {
            $('#mainContainer').css('transition', 'transform 0.3s ease');
        }, 300);
    });
});




