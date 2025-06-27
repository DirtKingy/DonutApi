<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donut API View</title>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const donutList = document.getElementById('donut-list');
        const pagination = document.getElementById('pagination');
        const errorBox = document.getElementById('errors');
        const form = document.getElementById('donut-form');
        let currentPage = 1;

        function loadDonuts(page = 1) {
            fetch(`/api/donuts?page=${page}`, { cache: "no-store" })
                .then(response => response.json())
                .then(data => {
                    donutList.innerHTML = '';
                    data.data.forEach(donut => {
                        const item = document.createElement('li');
                        item.innerHTML = `
                            <strong>${donut.name}</strong> - Approval: ${donut.seal_of_approval} - €${donut.price}
                            ${donut.image_url ? `<br><img src="${donut.image_url}" style="max-height: 100px;">` : ''}
                            <br><button class="delete-btn" data-id="${donut.id}">Delete</button>
                        `;
                        donutList.appendChild(item);
                    });

                    document.querySelectorAll('.delete-btn').forEach(btn => {
                        btn.addEventListener('click', async (e) => {
                            const id = e.target.getAttribute('data-id');
                            if (confirm('Weet je zeker dat je deze donut wilt verwijderen?')) {
                                const response = await fetch(`/api/donuts/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'Accept': 'application/json',
                                    },
                                });
                                if (response.ok && response.status !== 204) {
                                    loadDonuts(currentPage);
                                } else if (response.status === 204) {
                                    loadDonuts(currentPage);
                                } else {
                                    const text = await response.text();
                                    errorBox.textContent = 'Kon donut niet verwijderen: ' + text;
                                }
                            }
                        });
                    });

                    pagination.innerHTML = '';
                    data.meta.links.forEach(link => {
                        if (!link.url) return;

                        const btn = document.createElement('button');
                        btn.innerHTML = link.label;
                        btn.disabled = link.active;
                        btn.addEventListener('click', e => {
                            e.preventDefault();
                            const urlParams = new URL(link.url).searchParams;
                            const page = urlParams.get('page');
                            currentPage = page;
                            loadDonuts(page);
                        });
                        pagination.appendChild(btn);
                    });
                });
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            errorBox.innerHTML = '';
            const formData = new FormData(form);

            const response = await fetch('/api/donuts', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const text = await response.text();

            if (response.status === 201) {
                form.reset();
                loadDonuts(currentPage);
            } else if (response.status === 422) {
                const json = JSON.parse(text);
                for (let key in json.errors) {
                    const msg = document.createElement('div');
                    msg.textContent = `${key}: ${json.errors[key].join(', ')}`;
                    errorBox.appendChild(msg);
                }
            } else {
                errorBox.textContent = 'Er is iets misgegaan bij het toevoegen.';
            }
        });

        loadDonuts(currentPage);
    });
    </script>
</head>
<body>
    <h1>Royal Donuts (via API)</h1>

    <div id="errors" style="color: red;"></div>

    <form method="POST" id="donut-form" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Donut name" required><br>
        <input type="number" name="seal_of_approval" min="1" max="5" placeholder="Approval (1–5)" required><br>
        <input type="number" name="price" step="0.01" placeholder="Price (€)" required><br>
        <input type="file" name="image" accept="image/*"><br>
        <button type="submit">Add Donut</button>
    </form>

    <h2>Donuts</h2>
    <ul id="donut-list"></ul>

    <div id="pagination" style="margin-top: 1rem;"></div>
</body>
</html>
