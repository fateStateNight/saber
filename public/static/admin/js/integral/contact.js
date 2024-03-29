define(["jquery", "easy-admin"], function ($, ea) {
  var init = {
    index_url: ea.url('integral.douorder/index'),
    reward_url: ea.url('integral.douorder/reward'),
  };
// endpoint allowing to build the URL
const endpoint = 'https://www.childrendream.cn/manage/integral.contact/searchContact';
// limit capping the number of results returned from the call to the API
const limit = 10;

// form handling the submit event
const form = document.querySelector('form');
// section detailing the books
const section = document.querySelector('section');

// function to show the books fetched through the API
const showBooks = (books) => {
  // for each book retrieve the name, author and year of first publication
  // describe the details in a wrapping container and following an SVG drawing the book from one of the defined graphics
  /*section.innerHTML = books
    .map(({ title, author_name: author, first_publish_year: year }, index) => `
    <article style="animation-delay: ${0.1 * index}s;">
      <svg viewBox="0 0 25 125" width="25" height="125">
          <use href="#book-${Math.ceil(Math.random() * 4)}"></use>
      </svg>
      <div>
          <h1>${title.length > 45 ? `${title.substring(0, 45)}...` : title}</h1>
          <h2>${author || 'Unknown'}</h2>
          <p>${year || 'Unknown'}</p>
      </div>
    </article>
    `)
    .join('');*/
  section.innerHTML = `
  <article>
  <h3>此信息属于本公司员工的联系方式</h3>
  </article>
  `;
};

// function to show the lack of books
const showMissingBooks = () => {
  form.reset();
  section.innerHTML = `
  <article>
  <h3>此信息未知，不属于本公司员工信息</h3>
  </article>
  `;
};

// function to show the error message
const showError = (error) => {
  form.reset();
  section.innerHTML = `
    <article>
      <h3>服务器繁忙，请稍后再试！</h3>
    </article>
  `;
};

// on submit prevent the default behavior and proceed fetching the necessary information
const handleSubmit = (e) => {
  e.preventDefault();
  const title = form.querySelector('input').value;
  if (title) {
    // remove existing content from the section
    section.innerHTML = '<article><h3>查找中...</h3></article>';
    const url = `${endpoint}?title=${title}`;
    /* following the fetch request handle the following cases
    - the request returns an array of results, call a function to add them through article and svg elements
    - the request returns an empty array, call a function to detail as such
    - the request fails, show an error message
    */
    fetch(url)
      .then(response => response.json())
      .then(data => {
        console.log(data);
        if (data.code === 0) {
          showBooks(data);
        } else {
          showMissingBooks(data);
        }
      })
      .catch(err => showError(err));
  }
};
form.addEventListener('submit', handleSubmit);

  var Controller = {
    index: function () {
      ea.listen();
    }
  };
  return Controller;
});