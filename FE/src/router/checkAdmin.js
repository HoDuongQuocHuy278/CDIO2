import axios from "@/axios";

export default function (to, from, next) {
  axios
    .get("admin/check-token")
    .then((res) => {
      if (res.data.status) {
        localStorage.setItem("ho_ten_admin", res.data.ho_ten);
        localStorage.setItem("hinh_anh_admin", res.data.hinh_anh);
        next();
      } else {
        localStorage.removeItem("key_admin");
        next("/");
      }
    })
    .catch(() => {
      localStorage.removeItem("key_admin");
      next("/");
    });
}
