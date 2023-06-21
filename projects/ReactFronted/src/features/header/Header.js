import {Link} from 'react-router-dom';
import {useSelector} from 'react-redux';
import {selectLogged} from "../../slices/accountSlice";

function AppHeader() {
    const logged = useSelector(selectLogged);
    return (
        <header className="bg-black w-100 gx-0 py-1 row align-items-center">
            <Link to="/" className="col-2 offset-5 text-decoration-none">
                <h2 className="text-white text-center">Card Game</h2>
            </Link>

            <nav className="col-2 offset-3 ">
                <Link to="/games"><i className="fa-solid fa-xl fa-gamepad text-white m-2"></i></Link>
                {
                    logged ?
                        (<Link to="/profile"><i className="fa-solid fa-xl fa-user text-light"></i></Link>) :
                        (<Link to="/login"><i className="fa-solid fa-xl fa-right-to-bracket text-light"></i></Link>)
                }
            </nav>
        </header>
    );

}

export default AppHeader;
