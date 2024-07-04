document.addEventListener("DOMContentLoaded", function() {
    function loadOrders() {
        const orderListContainer = document.getElementById("order-list");
        if (orderListContainer) {
            fetch('../backend/get_orders.php')
            .then(response => response.json())
            .then(data => {
                orderListContainer.innerHTML = "";
                data.orders.forEach(order => {
                    const orderItem = document.createElement("tr");
                    orderItem.innerHTML = `
                        <td>${order.book_title}</td>
                        <td>${order.name}</td>`;
                    orderListContainer.appendChild(orderItem);
                });
            })
            .catch(error => console.error('Error:', error));
        }
    }

    if (document.getElementById("order-button")) {
        const orderButton = document.getElementById("order-button");
        orderButton.addEventListener("click", function() {
            const bookTitle = document.getElementById("book-title").value;
            const name = document.getElementById("name").value;

            fetch('../backend/proses.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `book-title=${encodeURIComponent(bookTitle)}&name=${encodeURIComponent(name)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Order placed successfully');
                    loadOrders();
                } else {
                    alert(data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }

    if (document.getElementById("order-list")) {
        loadOrders();
    }

    if (document.getElementById("add-book-button")) {
        const addBookButton = document.getElementById("add-book-button");
        addBookButton.addEventListener("click", function() {
            const newBookName = document.getElementById("new-book-name").value;
            const newBookPublisher = document.getElementById("new-book-publisher").value;
            const newBookYear = document.getElementById("new-book-year").value;
            const bookCover = document.getElementById("book-cover").files[0];

            const formData = new FormData();
            formData.append('new-book-name', newBookName);
            formData.append('new-book-publisher', newBookPublisher);
            formData.append('new-book-year', newBookYear);
            formData.append('book-cover', bookCover);

            fetch('../backend/add_book.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'data_books.html';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }

    if (document.getElementById("book-list")) {
        function loadBooks() {
            fetch('../backend/get_books.php')
            .then(response => response.json())
            .then(data => {
                const bookListContainer = document.getElementById("book-list");
                bookListContainer.innerHTML = "";
                data.books.forEach(book => {
                    const bookItem = document.createElement("tr");
                    bookItem.innerHTML = `
                        <td>${book.name}</td>
                        <td>${book.publisher}</td>
                        <td>${book.year}</td>
                        <td><img src="../buku/${book.file}" alt="Cover of ${book.name}" width="50"></td>
                        <td>
                            <button onclick="editBook('${book.file}', '${book.name}', '${book.publisher}', '${book.year}')">Edit</button>
                            <button onclick="deleteBook('${book.file}')">Delete</button>
                        </td>`;
                    bookListContainer.appendChild(bookItem);
                });
            })
            .catch(error => console.error('Error:', error));
        }

        loadBooks();

        window.editBook = function(file, name, publisher, year) {
            window.location.href = `edit_book.html?file=${file}&name=${name}&publisher=${publisher}&year=${year}`;
        };

        window.deleteBook = function(file) {
            fetch('../backend/delete_book.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ file })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadBooks();
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        };
    }

    if (document.getElementById("edit-book-form")) {
        const urlParams = new URLSearchParams(window.location.search);
        const file = urlParams.get('file');
        const name = urlParams.get('name');
        const publisher = urlParams.get('publisher');
        const year = urlParams.get('year');

        document.getElementById("edit-book-id").value = file;
        document.getElementById("edit-book-name").value = name;
        document.getElementById("edit-book-publisher").value = publisher;
        document.getElementById("edit-book-year").value = year;

        const editBookButton = document.getElementById("edit-book-button");
        editBookButton.addEventListener("click", function() {
            const editedBookId = document.getElementById("edit-book-id").value;
            const editedBookName = document.getElementById("edit-book-name").value;
            const editedBookPublisher = document.getElementById("edit-book-publisher").value;
            const editedBookYear = document.getElementById("edit-book-year").value;
            const editedBookCover = document.getElementById("edit-book-cover").files[0];

            const formData = new FormData();
            formData.append('edit-book-id', editedBookId);
            formData.append('edit-book-name', editedBookName);
            formData.append('edit-book-publisher', editedBookPublisher);
            formData.append('edit-book-year', editedBookYear);
            if (editedBookCover) {
                formData.append('edit-book-cover', editedBookCover);
            }

            fetch('../backend/edit_book.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Book updated successfully');
                    window.location.href = 'data_books.html';
                } else {
                    alert('Failed to update book');
                    console.error(data.error);
                }
            })
            .catch(error => {
                alert('An error occurred while updating the book');
                console.error('Error:', error);
            });
        });
    }
});
