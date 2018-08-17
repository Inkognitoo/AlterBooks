// "use strict";
//
// import axios from 'axios';
//
// (function () {
//
//     let request = axios.create({
//         headers: {'Authorization': 'Bearer ' + document.getElementsByName('api_token')[0].content}
//     });
//
//     let deleteButton = document.getElementById('review-delete');
//
//     if (deleteButton !== null) {
//         deleteButton.onclick = deleteReview;
//     }
//
//     /**
//      * Обрабатываем нажатие на кнопку восстановления
//      *
//      */
//     function deleteReview() {
//         let review_id = parseInt(this.dataset.reviewId);
//
//         deleteApiReview(review_id)
//             .then(function (response) {
//                 addRestoring();
//             })
//             .catch(function (error) {
//                 console.log(error);
//             });
//     }
//
//     /**
//      * Api запрос для восстановления рецензии
//      *
//      * @param {int} review_id идентификатор рецензии
//      * @returns {Promise<any>}
//      */
//     function deleteApiReview(review_id) {
//         let url = `/api/v1/review/id${review_id}/delete`;
//
//         return new Promise(function (resolve, reject) {
//             request.delete(url)
//                 .then(function (response) {
//                     if (response.data.success) {
//                         resolve(response.data);
//                     } else {
//                         reject(response.data)
//                     }
//                 })
//                 .catch(function (error) {
//                     reject(error);
//                 });
//         });
//     }
//
//     /**
//      * Показываем восстановленную рецензию
//      */
//     function addRestoring() {
//         let review_text = document.getElementById('review-text');
//         let review_shield = document.getElementById('review-shield');
//         review_shield.style.height = (review_text.offsetHeight + 25.5) + 'px';
//     }
// })();
